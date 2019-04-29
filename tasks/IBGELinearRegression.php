<?php

namespace tasks;
require_once 'Task.php';

class IBGELinearRegression implements Task
{
    public function processing($nome)
    {
        $nome = explode(" ", $nome);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v2/censos/nomes/" . $nome[0] . "?sexo=F");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $resposta = curl_exec($curl);
        curl_close($curl);

        $frequenciaFeminino = 0;
        foreach (json_decode($resposta, true)[0]['res'] as $frequenciaByAno){
            $frequenciaFeminino += $frequenciaByAno['frequencia'];
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://servicodados.ibge.gov.br/api/v2/censos/nomes/" . $nome[0] . "?sexo=M");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $resposta = curl_exec($curl);
        curl_close($curl);
        $frequenciaMasculina = 0;
        foreach (json_decode($resposta, true)[0]['res'] as $frequenciaByAno){
            $frequenciaMasculina += $frequenciaByAno['frequencia'];
        }

        if($frequenciaFeminino > $frequenciaMasculina){
            return 'feminino';
        }

        if($frequenciaMasculina > $frequenciaFeminino){
            return 'masculino';
        }

        return null;
    }
}