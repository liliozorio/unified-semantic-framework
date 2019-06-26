<?php

namespace method;
require_once 'Method.php';

class IBGELinearRegressionMethod implements \Method
{
    public function executeMethod($person)
    {
        if ($person->getAttribute('nome') == null ||
            ($person->getAttribute('sexo') != null && $person->getAttribute('nome') != null)
        ) {
            return;
        }

        $name = $person->getAttribute('nome');
        $name = explode(" ", $name);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v2/censos/nomes/" . $name[0] . "?sexo=F");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $answer = curl_exec($curl);
        curl_close($curl);

        $feminineFrequency = 0;
        $arr_answer = json_decode($answer, true);

        if (!empty($arr_answer)) {
            foreach ($arr_answer[0]['res'] as $frequencyPerYear) {
                $feminineFrequency += $frequencyPerYear['frequencia'];
            }
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v2/censos/nomes/" . $name[0] . "?sexo=M");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $answer = curl_exec($curl);
        curl_close($curl);

        $maleFrequency = 0;
        $arr_answer = json_decode($answer, true);
        if (!empty($arr_answer)) {
            foreach ($arr_answer[0]['res'] as $frequencyPerYear) {
                $maleFrequency += $frequencyPerYear['frequencia'];
            }
        }

        if ($feminineFrequency > $maleFrequency) {
            $sex = 'feminino';
        }

        if ($maleFrequency > $feminineFrequency) {
            $sex = 'masculino';
        }

        $person->setAttribute('sexo', $sex);
    }
}