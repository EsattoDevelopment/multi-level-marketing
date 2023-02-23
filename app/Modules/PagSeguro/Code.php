<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\PagSeguro;

use App\Models\Sistema;
use App\Models\ItensPedido;

class Code
{
    protected $data = [];
    protected $sandbox;
    protected $sistema;

    public function __construct(ItensPedido $item, $sandbox = false)
    {
        $this->sistema = Sistema::findOrFail(1);
        $this->data['token'] = '9629159CD37D48EF86BFD365CE6EE157';

        if ($sandbox) {
            $this->data['token'] = 'E1DDA9912B4E4532B5428EBBD1BA8A2F';
        }

        $this->data['email'] = 'finan@galaxyclube.com.br';
        $this->data['currency'] = 'BRL';
        $this->data['itemId1'] = $item->quantidade;
        $this->data['itemQuantity1'] = $item->quantidade;
        $this->data['itemDescription1'] = 'Pedido #'.$item->pedido_id.' - '.$item->name_item;
        $this->data['itemAmount1'] = mascaraMoeda($this->sistema->moeda, $item->valor_total, 2);

        $this->sandbox = $sandbox;
    }

    public function getCode()
    {
        $url = 'https://ws.pagseguro.uol.com.br/v2/checkout/';

        if ($this->sandbox) {
            $url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout/';
        }

        $data = http_build_query($this->data);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $xml = curl_exec($curl);

        if ($xml == 'Unauthorized') {
            return [false, 'message' => 'Não autorizado!'];
        }

        curl_close($curl);

        $xml = simplexml_load_string($xml);

        if (count($xml->error) > 0) {
            return [false, 'message' => 'Dados Inválidos '.$xml->error->message];
        }

        return $xml->code;
    }
}
