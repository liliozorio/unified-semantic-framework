<?php

namespace method;
require_once 'Method.php';

class IBGELinearRegressionMethod implements \Method
{
    public function executeMethod($pessoa)
    {
        if ($pessoa->getAttribute('nome') == null ||
            ($pessoa->getAttribute('sexo') != null && $pessoa->getAttribute('nome') != null)
        ) {
            return;
        }

        $nome = $pessoa->getAttribute('nome');
        $nome = explode(" ", $nome);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v2/censos/nomes/" . $nome[0] . "?sexo=F");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $resposta = curl_exec($curl);
        curl_close($curl);

        $frequenciaFeminino = 0;
        $arr_resposta = json_decode($resposta, true);

        if (!empty($arr_resposta)) {
            foreach ($arr_resposta[0]['res'] as $frequenciaByAno) {
                $frequenciaFeminino += $frequenciaByAno['frequencia'];
            }
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v2/censos/nomes/" . $nome[0] . "?sexo=M");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $resposta = curl_exec($curl);
        curl_close($curl);

        $frequenciaMasculina = 0;
        $arr_resposta = json_decode($resposta, true);
        if (!empty($arr_resposta)) {
            foreach ($arr_resposta[0]['res'] as $frequenciaByAno) {
                $frequenciaMasculina += $frequenciaByAno['frequencia'];
            }
        }

        if ($frequenciaFeminino > $frequenciaMasculina) {
            $sexo = 'feminino';
        }

        if ($frequenciaMasculina > $frequenciaFeminino) {
            $sexo = 'masculino';
        }

        $pessoa->setAttribute('sexo', $sexo);
    }
}