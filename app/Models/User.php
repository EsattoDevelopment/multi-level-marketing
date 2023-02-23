<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use App\Saude\Domains\Medico;
use Illuminate\Bus\Queueable;
use App\Saude\Domains\Dependente;
use App\Saude\Domains\Procedimento;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes, Notifiable, Queueable;
    use EntrustUserTrait {
        EntrustUserTrait::restore insteadof SoftDeletes;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'data_nasc',
        'termo',
        'cpf',
        'cnpj',
        'inscricao_estadual',
        'sexo',
        'profissao',
        'codigo',
        'rg',
        'telefone',
        'celular',
        'password',
        'indicador_id',
        'parceiro_id',
        'empresa_id',
        'empresa',
        'status',
        'titulo_id',
        'qualificado',
        'equipe_preferencial',
        'image',
        'equipe_predefinida',
        'tipo',
        'sen-dependente',
        'whatsapp',
        'estado_civil',
        'clinica_id',
        'recebe_pagamento_binario',
        'lado',
        'status_cpf',
        'image_cpf',
        'conta',
        'avisa_recebimento_rentabilidade',
        'status_selfie',
        'status_comprovante_endereco',
        'google2fa_login',
        'estrangeiro',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'avisa_recebimento_rentabilidade' => 'boolean',
        'google2fa_login' => 'boolean',
        'estrangeiro' => 'boolean',
    ];

    // Specify Slack Webhook URL to route notifications to

    /**
     * @return mixed
     */
    public function routeNotificationForSlack()
    {
        return env('SLACK_WEBHOOK_URL');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'deleted_at'];

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function responsavel()
    {
        return $this->hasMany(Responsaveis::class, 'user_id');
    }

    public function getResponsavelDocNaoEnviadoAttribute()
    {
        if ($this->getIdadeAttribute() < 18) {
            $responsavel = $this->responsavel()->get();
            if ($responsavel->count() > 0) {
                $responsavel = $responsavel->where('status', 0)->first();
                if ($responsavel != null) {
                    if ($responsavel->count() > 0) {
                        //tem responsavel cadastrado mas não está ativo, então verifico se não envio alguma movimentação
                        if ($responsavel->status_selfie == null || $responsavel->status_documento == null || $responsavel->status_documento_representacao == null) {
                            return 1;
                        } else {
                            return 0;
                        }
                    } else {
                        //não tem responsavel inativo, até agora só ta cadastrando um responsavel, quando for cadastrar mais de um responsavel tem que rever essa parte
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else { //pessoa é menor de idade e não tem o responsavel cadastrado
                return 1;
            }
        } else { //não é menor de idade, então não precisa verificar o responsavel
            return 0;
        }
    }

    public function getResponsavelDocAguardandoAttribute()
    {
        if ($this->getIdadeAttribute() < 18) {
            $responsavel = $this->responsavel()->get();
            if ($responsavel->count() > 0) {
                $responsavel = $responsavel->where('status', 0)->first();
                if ($responsavel->count() > 0) {
                    //tem responsavel cadastrado mas não está ativo, então verifico se tem algum documento em analise
                    if ($responsavel->status_selfie == 'em_analise' || $responsavel->status_documento == 'em_analise' || $responsavel->status_documento_representacao == 'em_analise') {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    //não tem responsavel inativo, até agora só ta cadastrando um responsavel, quando for cadastrar mais de um responsavel tem que rever essa parte
                    return 0;
                }
            } else { //pessoa é menor de idade e não tem o responsavel cadastrado
                return 1;
            }
        } else { //não é menor de idade, então não precisa verificar o responsavel
            return 0;
        }
    }

    public function getResponsavelDocAprovadoAttribute()
    {
        if ($this->getIdadeAttribute() < 18) {
            $responsavel = $this->responsavel()->get();
            if ($responsavel->count() > 0) {
                if ($responsavel->where('status', 1)->first() != null) {
                    //toda documentação está OK e aprovada
                    return 1;
                } else {
                    $responsavel = $responsavel->where('status', 0)->first();
                    if ($responsavel->count() > 0) {
                        //tem responsavel cadastrado mas não está ativo, então verifico se tem algum documento em analise
                        if ($responsavel->status_selfie == 'validado' || $responsavel->status_documento == 'validado' || $responsavel->status_documento_representacao == 'validado') {
                            return 1;
                        } else {
                            return 0;
                        }
                    } else {
                        //não tem responsavel inativo, até agora só ta cadastrando um responsavel, quando for cadastrar mais de um responsavel tem que rever essa parte
                        return 0;
                    }
                }
            } else { //pessoa é menor de idade e não tem o responsavel cadastrado
                return 1;
            }
        } else { //não é menor de idade, então não precisa verificar o responsavel
            return 0;
        }
    }

    public function getResponsavelDocReprovadoAttribute()
    {
        if ($this->getIdadeAttribute() < 18) {
            $responsavel = $this->responsavel()->get();
            if ($responsavel->count() > 0) {
                $responsavel = $responsavel->where('status', 0)->first();
                if ($responsavel->count() > 0) {
                    //tem responsavel cadastrado mas não está ativo, então verifico se tem algum documento em analise
                    if ($responsavel->status_selfie == 'recusado' || $responsavel->status_documento == 'recusado' || $responsavel->status_documento_representacao == 'recusado') {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    //não tem responsavel inativo, até agora só ta cadastrando um responsavel, quando for cadastrar mais de um responsavel tem que rever essa parte
                    return 0;
                }
            } else { //pessoa é menor de idade e não tem o responsavel cadastrado
                return 1;
            }
        } else { //não é menor de idade, então não precisa verificar o responsavel
            return 0;
        }
    }

    public function setPasswordAttribute($password)
    {
        if (Hash::needsRehash($password)) {
            $this->attributes['password'] = bcrypt($password);
        } else {
            $this->attributes['password'] = $password;
        }
    }

    public function getStatusStringAttribute($value)
    {
        if ($this->attributes['status'] == 1) {
            return 'Sim';
        } else {
            return 'Não';
        }
    }

    public function getNameAttribute($value)
    {
        if (isset($this->attributes['cpf']) && strlen($this->attributes['cpf']) > 14) {
            return $this->attributes['empresa'] ?? $value;
        } else {
            return $value;
        }
    }

    public function getIsEmpresaAttribute($value)
    {
        $result = false;
        if (isset($this->attributes['cpf']) && strlen($this->attributes['cpf']) > 14) {
            $result = true;
        }

        return $result;
    }

    public function getValidadoAttribute()
    {
        $doc = false;

        if ($this->attributes['status_selfie'] == 'validado' && $this->attributes['status_comprovante_endereco'] == 'validado' && $this->attributes['status_cpf'] == 'validado') {
            $doc = true;
        }

        $data = Carbon::parse(implode('-', array_reverse(explode('/', $this->attributes['data_nasc']))));

        if ($data->diffInYears(Carbon::now()) < 18) {
            $responsavel = self::responsavel()->where('status', 0)->first();
            if ($responsavel) {
                if ($responsavel->status_documento_representacao != 'validado' || $responsavel->status_selfie != 'validado' || $responsavel->status_documento != 'validado') {
                    $doc = false;
                }
            }
        }

        return $doc;
    }

    public function getDocumentoAttribute()
    {
        if ($this->attributes['status_cpf'] == 'validado') {
            return true;
        }

        return false;
    }

    public function getIdentidadeAttribute()
    {
        if ($this->attributes['status_selfie'] == 'validado' && $this->attributes['status_cpf'] == 'validado') {
            return true;
        }

        return false;
    }

    public function getEditarEnderecoAttribute()
    {
        if ($this->attributes['status_comprovante_endereco'] == 'validado' || $this->attributes['status_comprovante_endereco'] == 'em_analise') {
            return false;
        }

        return true;
    }

    public function getStatusAtivoAttribute($value)
    {
        if ($this->attributes['status'] == 1) {
            return 'ATIVO';
        } else {
            return 'INATIVO';
        }
    }

    public function getCorStatusAttribute($value)
    {
        if ($this->attributes['status'] == 1) {
            return '32CD32';
        } else {
            return 'A9A9A9';
        }
    }

    public function getPrimeiroNomeAttribute()
    {
        return ucfirst(explode(' ', $this->attributes['name'])[0]);
    }

    public function getEstadoCivilExtensoAttribute()
    {
        return config('constants.estado_civil')[$this->attributes['estado_civil']];
    }

    public function getNascimentoAttribute()
    {
        return Carbon::parse(implode('-', array_reverse(explode('/', $this->attributes['data_nasc']))));
    }

    public function getIdadeAttribute()
    {
        if (strlen($this->attributes['cpf']) > 14) {
            return 100;
        } else {
            return $this->nascimento->diffInYears(\Carbon\Carbon::now());
        }
    }

    /*public function getTipoAttribute($value)
    {
        if($value){
            return 'Comum';
        }else{
            return 'Empresa';
        }
    }*/

    public function getQualificadoStringAttribute()
    {
        if ($this->attributes['qualificado'] == 1) {
            return 'Sim';
        } else {
            return 'Não';
        }
    }

    public function getEstadoCivilExValue()
    {
        $estado = 'Nada consta';
        switch ($this->attributes['estado_civil']) {
            case 1:
                $estado = 'Solteiro';
                break;
            case 2:
                $estado = 'Casado';
                break;
            case 3:
                $estado = 'União estável';
                break;
            case 4:
                $estado = 'Divorciado(a)';
                break;
            case 5:
                $estado = 'Viuvo(a)';
                break;
        }

        return $estado;
    }

    public function getTipoUserValue()
    {
        $estado = '';
        switch ($this->attributes['tipo']) {
            case 1:
                $estado = 'Comum';
                break;
            case 2:
                $estado = 'Empresa';
                break;
            case 3:
                $estado = 'Clinica';
                break;
            case 4:
                $estado = 'Call Center';
                break;
        }

        return $estado;
    }

    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'medicos_clinicas', 'user_id', 'medico_id');
    }

    public function indicador()
    {
        return $this->belongsTo(self::class, 'indicador_id');
    }

    public function empresa()
    {
        return $this->belongsTo(self::class, 'empresa_id');
    }

    public function diretos()
    {
        return $this->hasMany(self::class, 'indicador_id');
    }

    public function diretosEsquerda()
    {
        return $this->diretos()->where('lado', 1);
    }

    public function diretosDireita()
    {
        return $this->diretos()->where('lado', 2);
    }

    public function consultores()
    {
        return $this->diretos()->whereHas('titulo', function ($query) {
            $query->where('habilita_rede', 1);
        });
    }

    public function diretosAprovados()
    {
        return $this->diretos()->where('status', 1);
    }

    public function diretosEsquerdaAprovados()
    {
        return $this->diretosEsquerda()->where('status', 1);
    }

    public function diretosDireitaAprovados()
    {
        return $this->diretosDireita()->where('status', 1);
    }

    public function funcionarios()
    {
        return $this->hasMany(self::class, 'empresa_id');
    }

    public function pendentes()
    {
        return $this->diretos()->get()->where('status', 0);
    }

    public function endereco()
    {
        return $this->hasOne(EnderecosUsuarios::class);
    }

    public function dadosBancarios()
    {
        return $this->hasMany(DadosBancarios::class);
    }

    public function titulo()
    {
        return $this->belongsTo(Titulos::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class)->withTrashed();
    }

    public function transferencias()
    {
        return $this->hasMany(Transferencias::class);
    }

    public function milhas()
    {
        return $this->hasMany(Milhas::class, 'user_id');
    }

    public function pagamentos()
    {
        return $this->hasMany(DadosPagamento::class, 'responsavel_user_id');
    }

    public function responsavelMovimentos()
    {
        return $this->hasMany(Movimentos::class)->orderBy('id');
    }

    public function movimentos()
    {
        return $this->hasMany(Movimentos::class, 'user_id')->orderBy('id');
    }

    public function extratoBinario()
    {
        return $this->hasMany(ExtratoBinario::class)->orderBy('id');
    }

    public function extratoB()
    {
        return $this->hasMany(ExtratoBinario::class)->orderBy('id');
    }

    public function extratoBinarioSaldo()
    {
        return $this->extratoBinario->last();
    }

    public function redeBinario()
    {
        return $this->hasOne(RedeBinaria::class)->orderBy('id');
    }

    public function redeBinarioSelect()
    {
        return $this->hasOne(RedeBinaria::class)->orderBy('id');
    }

    public function redeBinarioEsquerda()
    {
        return $this->hasMany(RedeBinaria::class, 'esquerda')->orderBy('id');
    }

    public function redeBinarioDireita()
    {
        return $this->hasMany(RedeBinaria::class, 'direita')->orderBy('id');
    }

    public function hoteis()
    {
        return $this->hasMany(Hotel::class)->orderBy('id');
    }

    public function hotel()
    {
        return $this->hoteis()->whereFechado(0)->orderBy('id', 'desc')->first();
    }

    /**
     * @return mixed
     */
    public function hospedeEsquerda()
    {
        return $this->hasMany(Hospedes::class, 'esquerda')->orderBy('id');
    }

    public function hospedeDireita()
    {
        return $this->hasMany(Hospedes::class, 'direita')->orderBy('id');
    }

    public function reservas()
    {
        return $this->hasMany(PedidoPacote::class, 'user_id');
    }

    public function dependentes()
    {
        return $this->hasMany(Dependente::class, 'titular_id');
    }

    public function conjuge()
    {
        return $this->dependentes()->select(['id'])->where('parentesco', 1)->count();
    }

    public function filhos()
    {
        return $this->dependentes()->select(['id'])->where('parentesco', 2)->count();
    }

    public function setCpfAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['cpf'] = null;
        } else {
            $this->attributes['cpf'] = $value;
        }
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'user_id');
    }

    public function contratoVigente()
    {
        return $this->hasMany(Contrato::class, 'user_id')->with('mensalidades')->first();
    }

    public function contratoVigenteOnly()
    {
        return $this->hasMany(Contrato::class, 'user_id')->orderBy('id', 'desc')->take(1)->first();
    }

    public function procedimentos()
    {
        return $this->belongsToMany(Procedimento::class, 'procedimento_clinica', 'user_id');
    }

    public function clinica()
    {
        return $this->belongsTo(self::class, 'clinica_id');
    }

    public function pontosPessoais()
    {
        return $this->hasMany(PontosPessoais::class)->orderBy('id');
    }

    public function extratoPessoais()
    {
        return $this->pontosPessoais->last();
    }

    public function pontosEquiparacao()
    {
        return $this->hasMany(PontosEquipeEquiparacao::class)->orderBy('id');
    }

    public function pontosUnilevel()
    {
        return $this->hasMany(PontosEquipeUnilevel::class)->orderBy('id');
    }

    public function extratoUnilevel()
    {
        return $this->pontosUnilevel->last();
    }

    public function ultimoMovimento()
    {
        $ultimoMovimento = $this->hasMany(Movimentos::class)->orderBy('id', 'desc')->limit(1)->get();

        $ultimoMovimento = $ultimoMovimento->first() instanceof Movimentos ? $ultimoMovimento->first() : null;

        return $ultimoMovimento;
    }

    public function ultimoPontosUnilevel()
    {
        $ultimoPontosUnilevel = $this->hasMany(PontosEquipeUnilevel::class)->orderBy('id', 'desc')->limit(1)->get();

        $ultimoPontosUnilevel = $ultimoPontosUnilevel->first() instanceof PontosEquipeUnilevel ? $ultimoPontosUnilevel->first() : null;

        return $ultimoPontosUnilevel;
    }

    /**
     * @return mixed
     */
    public function pontosEquipe()
    {
        return $this->pontosEquiparacao->last();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany(Role::class,'role_user', 'user_id','role_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeRoleAdministrador($query)
    {
        return $query->whereHas('role', function ($query)
                                {
                                    $query->whereName('master');
                                });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeRoleEmpresa($query)
    {
        return $query->whereHas('role', function ($query)
                                {
                                    $query->whereName('admin');
                                });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeRolesUsuarios($query)
    {
        return $query->whereHas('role', function ($query)
                                {
                                    $query->whereNotIn('name', ['master','admin']);
                                });
    }

    public function scopeRolesNaoAdministrador($query)
    {
        return $query->whereHas('role', function ($query)
        {
            $query->where('name', '<>', 'master');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', '<>', 0);
    }
}
