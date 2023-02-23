<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Hospedes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.hotel.index', [
            'title' => 'Hoteis - Galaxy Clube',
            'dados' => Hotel::whereUserId(Auth::user()->id)->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $hotel = Hotel::with('usuario')->findOrFail($id);

            if (($hotel->user_id == Auth::user()->id) || Auth::user()->can(['master', 'admin'])) {
                $objHospede = new Hospedes();
                $quartoHotel = $objHospede->whereHotelId($id)->first();

                return view('default.hotel.show', [
                        'title' => "Hotel #{$id} - Galaxy Clube",
                        'dados' => $hotel,
                        'quartos' => $objHospede->montaHotel($quartoHotel),
                    ]);
            } else {
                flash()->error('Você não tem permissão para acessar Hoteis de outros.');

                return redirect()->back();
            }
        } catch (ModelNotFoundException $e) {
            flash()->error('Hotel não existe');

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
