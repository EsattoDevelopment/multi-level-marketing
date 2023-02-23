<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = ['user_id', 'fechado'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quarto()
    {
        return $this->hasOne(Hospedes::class, 'hotel_id');
    }

    public function fecharHotel()
    {
        $this->fechado = 1;

        return $this->save();
    }

    public function hotelIndicador($id)
    {
        Log::info('busca hotel patrocinador #'.$id);
        $hotel = $this->with('quarto')->whereUserId($id)->whereFechado(0)->first();

        if ($hotel) {
            Log::info('encontrou hotel #'.$hotel->id);

            return $hotel;
        } else {
            Log::info('não achou hotel patrocinador, partindo para outro');
            $usuario = User::find($id);
            $usuarioIndicador = $usuario->indicador()->first();

            return $this->hotelIndicador($usuarioIndicador->id);
        }
    }

    public function getCriadoAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public function getDataFechadoAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->format('d/m/Y');
    }

    public function getFechadoStringAttribute()
    {
        if ($this->attributes['fechado'] == 0) {
            return 'Aberto';
        } else {
            return 'Fechado (Ciclou)';
        }
    }
}
