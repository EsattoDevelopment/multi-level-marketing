<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;

class Hospedes extends Model
{
    protected $table = 'hospedes';

    protected $primaryKey = 'hotel_id';

    protected $fillable = ['hotel_id', 'esquerda', 'direita'];

    private $arrayHotel = [];

    public function quartoEsquerdo()
    {
        return $this->belongsTo(self::class, 'esquerda', 'hotel_id');
    }

    public function quartoDireito()
    {
        return $this->belongsTo(self::class, 'direita', 'hotel_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function userEsquerda()
    {
        return $this->belongsTo(User::class, 'esquerda');
    }

    public function userDireita()
    {
        return $this->belongsTo(User::class, 'direita');
    }

    /**
     * Posiciona hotel/hospede em um quarto
     * O hospedeID é referencia a um hotel_id, no entendo aqui o hotel se torna hospede.
     * @param $hospedeID
     * @return bool
     */
    public function inserirHotel($hospedeID)
    {
        //TODO flag para setar posição encontrada
        Log::info('entrou para inserir hospede/hotel');
        $encontrou = false;

        //verifica se há vaga abaixo do quarto/hotel atual
        if (! $this->esquerda || ! $this->direita) {
            $encontrou = true;
        }

        if (! $encontrou) {
            $dadosHotelQuarto = $this->posicaoVaziaHotel($this);

            return $this->insereHospede($dadosHotelQuarto['quarto'], $hospedeID);
        } else {
            return $this->insereHospede($this, $hospedeID);
        }
    }

    /**
     * @param Hospedes $quarto
     * @param $hospedeID
     * @param bool $totalHospedes
     * @return bool
     */
    private function insereHospede(Hospedes $quarto, $hospedeID)
    {

        //TODO verifica se nó vazio esta a direita ou esquerda
        Log::info('entrou para inserir hospede/hotel, hospede/quarto:'.$hospedeID);
        if (! $quarto->esquerda) {
            $quarto->esquerda = $hospedeID;
        } elseif (! $quarto->direita) {
            $quarto->direita = $hospedeID;
        }

        //insere hospede
        $quarto->save();

        //cria um quarto no hotel
        $this->create([
            'hotel_id' => $hospedeID,
        ]);

        $quartoCriado = $this->whereHotelId($hospedeID)->first();

        Log::info('Posicionou hotel', $quarto->toArray());
        Log::info('Adicionou hotel/hospede #'.$quartoCriado->hotel_id);

        //TODO pega o quarto nivel superior do hotel para ver se pode ser ciclado
        $topoHotel = $this->buscaTopoHotel($quartoCriado);

        $dadosHotelQuarto = $this->posicaoQuantidadeHotel($topoHotel);

        $totalHospedes = $dadosHotelQuarto['totalHospedes'];

        return [
            'retorno' => true,
            'totalHospedes' => $totalHospedes,
            'quarto' => $topoHotel,
        ];
    }

    /**
     * @param Hospedes $quarto
     * @return array
     */
    private function posicaoQuantidadeHotel(Hospedes $quarto)
    {
        Log::info('Buscando posicao vazia e quantidade de quartos no hotel');
        Log::info('quarto hotel #'.$quarto->hotel_id);
        //flag para setar nós vazio encontrado
        $encontrou = false;
        $totalHospedes = 0;

        //verifica se há posição abaixo do nó atual
        if (! $quarto->esquerda || ! $quarto->direita) {
            Log::info('Encontrou posicao vazia');
            //$encontrou = true;
        }

        //TODO carrega hospede dos quartos e verifica vaga nos quartos/hoteis dele
        $profundidade = 0;
        $maxProfundidade = 4;

        if ($quarto->esquerda) {
            $quartoE = $this->encontraPosicaoVazioNumQuartos($quarto->quartoEsquerdo()->first(), $profundidade, $maxProfundidade);
        } else {
            $quartoE = ['quarto' => $quarto,
                'profundidade' => 0,
                'qtdLado' => 0,
            ];
        }

        if ($quarto->direita) {
            $quartoD = $this->encontraPosicaoVazioNumQuartos($quarto->quartoDireito()->first(), $profundidade, $maxProfundidade);
        } else {
            $quartoD = ['quarto' => $quarto,
                'profundidade' => 0,
                'qtdLado' => 0,
            ];
        }

        //compara e produra a menor profundidade
        if ($quartoE['profundidade'] <= $quartoD['profundidade']) {
            $quarto = $quartoE['quarto'];
        } elseif ($quartoE['profundidade'] > $quartoD['profundidade']) {
            $quarto = $quartoD['quarto'];
        }

        $totalHospedes = $quartoE['qtdLado'] + $quartoD['qtdLado'];

        return [
            'quarto' => $quarto,
            'totalHospedes' => $totalHospedes,
        ];
    }

    private function posicaoVaziaHotel(Hospedes $quarto)
    {
        Log::info('Entra para procurar posicao vazia, quarto/hotel #'.$quarto->hotel_id);

        //TODO carrega hospede dos quartos e verifica vaga nos quartos/hoteis dele
        $profundidade = 1;
        $maxProfundidade = 3;

        $quartoE = $this->encontraPosicaoVazioHotelQuartos($quarto->quartoEsquerdo()->first(), $profundidade, $maxProfundidade);
        $quartoD = $this->encontraPosicaoVazioHotelQuartos($quarto->quartoDireito()->first(), $profundidade, $maxProfundidade);

        //compara e produra a menor profundidade
        if ($quartoE['profundidade'] <= $quartoD['profundidade']) {
            $quarto = $quartoE['quarto'];
            Log::info('Quarto/hotel #'.$quartoE['quarto']->hotel_id.', é mais razo que quarto/hotel #'.$quartoD['quarto']->hotel_id);
        } elseif ($quartoE['profundidade'] > $quartoD['profundidade']) {
            $quarto = $quartoD['quarto'];
            Log::info('Quarto/hotel #'.$quartoD['quarto']->hotel_id.', é mais razo que quarto/hotel #'.$quartoE['quarto']->hotel_id);
        }

        return [
            'quarto' => $quarto,
        ];
    }

    /**
     * @param Hospedes $quarto
     * @param int $alturaMax
     * @return array
     */
    private function buscaTopoHotel(Hospedes $quarto, $alturaMax = 5)
    {
        Log::info('entrou para procurar nó superior, altura:'.$alturaMax);
        Log::info('----------Hotel #'.$quarto->hotel_id);
        //TODO carrega no superiror
        $noSuperior = $this->where('esquerda', $quarto->hotel_id)->orWhere('direita', $quarto->hotel_id)->first();

        //TODO verifica se é para subir mais
        if ($alturaMax > 1 && $noSuperior) {
            return $this->buscaTopoHotel($noSuperior, $alturaMax - 1);
        } elseif ($alturaMax == 1 || ! $noSuperior) {
            return $quarto;
        }
    }

    /**
     * @param Hospedes $quarto
     * @param $profundidade
     * @param $maxProfundidade
     * @return array
     */
    private function encontraPosicaoVazioNumQuartos(Hospedes $quarto, $profundidade, $maxProfundidade)
    {
        Log::info('quarto/hotel #'.$quarto->hotel_id);
        $atualProfundidade = $maxProfundidade - $profundidade;
        Log::info('********* profundidade -> '.$atualProfundidade);

        //inicializa quantidade de nó
        $qtdHospede = 0;

        //verifica se esta na profundidade maxima permitida apartir do nó inicial
        if ($atualProfundidade > 0) {

            //flag para setar nós vazio encontrado
            //$encontrou = false;

            //verifica se há posição abaixo do nó atual
            if (! $quarto->esquerda || ! $quarto->direita) {
                Log::info('Encontrou posicao vazia quarto #'.$quarto->hotel_id);
                //$encontrou = true;
            }

            //TODO verificar nós abaixo
            if ($quarto->esquerda) {
                $quartoE = $this->encontraPosicaoVazioNumQuartos($quarto->quartoEsquerdo()->first(), $profundidade + 1, $maxProfundidade);
            } else {
                $quartoE = ['quarto' => $quarto,
                    'profundidade' => $profundidade,
                    'qtdLado' => 0,
                ];
            }

            if ($quarto->direita) {
                $quartoD = $this->encontraPosicaoVazioNumQuartos($quarto->quartoDireito()->first(), $profundidade + 1, $maxProfundidade);
            } else {
                $quartoD = ['quarto' => $quarto,
                    'profundidade' => $profundidade,
                    'qtdLado' => 0,
                ];
            }

            //TODO verifica a menor profundidade
            if ($quartoE['profundidade'] <= $quartoD['profundidade']) {
                $quarto = $quartoE['quarto'];
            } elseif ($quartoE['profundidade'] > $quartoD['profundidade']) {
                $quarto = $quartoD['quarto'];
            }

            $qtdHospede = $quartoE['qtdLado'] + $quartoD['qtdLado'];
        } else {
            $qtdHospede = -1;
        }

        //TODO retorna quarto/hotel encontrado, sua profundidade e
        return [
            'quarto' => $quarto,
            'profundidade' => $profundidade,
            'qtdLado' => $qtdHospede + 1,
        ];
    }

    private function encontraPosicaoVazioHotelQuartos(Hospedes $quarto, $profundidade, $maxProfundidade)
    {
        Log::info('quarto/hotel #'.$quarto->hotel_id);
        Log::info('profundidade '.$profundidade);

        //flag para setar nós vazio encontrado
        $encontrou = false;

        //verifica se há posição abaixo do nó atual
        if (! $quarto->esquerda || ! $quarto->direita) {
            Log::info('Encontrou posicao vazia quarto #'.$quarto->hotel_id);
            $encontrou = true;
        }

        if (! $encontrou) {
            if ($profundidade <= $maxProfundidade) {

                //TODO verificar nós abaixo
                $quartoE = $this->encontraPosicaoVazioHotelQuartos($quarto->quartoEsquerdo()->first(), $profundidade + 1, $maxProfundidade);
                $quartoD = $this->encontraPosicaoVazioHotelQuartos($quarto->quartoDireito()->first(), $profundidade + 1, $maxProfundidade);

                //TODO verifica a menor profundidade
                if ($quartoE['profundidade'] <= $quartoD['profundidade']) {
                    Log::info("Comparacao {$quartoE['quarto']->hotel_id} - {$quartoE['profundidade']} <= {$quartoD['quarto']->hotel_id} - {$quartoD['profundidade']}");

                    $profundidade = $quartoE['profundidade'];
                    $quarto = $quartoE['quarto'];
                } elseif ($quartoE['profundidade'] > $quartoD['profundidade']) {
                    Log::info("Comparacao {$quartoE['quarto']->hotel_id} - {$quartoE['profundidade']} > {$quartoD['quarto']->hotel_id} - {$quartoD['profundidade']}");

                    $profundidade = $quartoD['profundidade'];
                    $quarto = $quartoD['quarto'];
                }
            } else {
                Log::info('Fim do quarto, lado cheio, é o ultimo quarto #'.$quarto->hotel_id);
                $profundidade++;
            }

            Log::info("Volta {$quarto->hotel_id}");
        }

        //TODO retorna quarto/hotel encontrado, sua profundidade e
        return [
            'quarto' => $quarto,
            'profundidade' => $profundidade,
        ];
    }

    public function montaHotel(Hospedes $quarto)
    {

        //TODO carrega hospede dos quartos e verifica vaga nos quartos/hoteis dele
        $profundidadeE = 1;
        $profundidadeD = 1;
        $maxProfundidade = 5;

        if ($quarto->esquerda) {
            //Log::warning('Profundidade E-'.$profundidadeE);
            //Log::warning('Hotel '.$quarto->esquerda);
            $this->arrayHotel[$profundidadeE][] = $quarto->quartoEsquerdo()->first()->hotel()->first()->usuario()->first();
            $this->montaHotelHospedes($quarto->quartoEsquerdo()->first(), $profundidadeE + 1, $maxProfundidade);
        }

        if ($quarto->direita) {
            //Log::warning('Profundidade D-'.$profundidadeD);
            //Log::warning('Hotel '.$quarto->direita);
            $this->arrayHotel[$profundidadeD][] = $quarto->quartoDireito()->first()->hotel()->first()->usuario()->first();
            $this->montaHotelHospedes($quarto->quartoDireito()->first(), $profundidadeD + 1, $maxProfundidade);
        }

        return $this->arrayHotel;
    }

    private function montaHotelHospedes(Hospedes $quarto, $profundidade, $maxProfundidade)
    {
        $atualProfundidade = $maxProfundidade - $profundidade;

        $profundidadeE = $profundidade;
        $profundidadeD = $profundidade;

        //verifica se esta na profundidade maxima permitida apartir do nó inicial
        if ($atualProfundidade > 0) {

            //TODO verificar nós abaixo
            if ($quarto->esquerda) {
                //Log::warning('Profundidade E-'.$profundidadeE);
                //Log::warning('Hotel '.$quarto->esquerda);
                $this->arrayHotel[$profundidadeE][] = $quarto->quartoEsquerdo()->first()->hotel()->first()->usuario()->first();
                $this->montaHotelHospedes($quarto->quartoEsquerdo()->first(), $profundidadeE + 1, $maxProfundidade);
            }

            if ($quarto->direita) {
                //Log::warning('Profundidade D-'.$profundidadeD);
                //Log::warning('Hotel '.$quarto->direita);
                $this->arrayHotel[$profundidadeD][] = $quarto->quartoDireito()->first()->hotel()->first()->usuario()->first();
                $this->montaHotelHospedes($quarto->quartoDireito()->first(), $profundidadeD + 1, $maxProfundidade);
            }
        }
    }
}
