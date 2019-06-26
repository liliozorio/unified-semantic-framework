<?php

namespace tasks;
require_once __DIR__.'/../tasks/Task.php';

class CityCanonicaNameTask implements Task
{
    public function processing($cidade)
    {
        $text = $cidade;
        $format = 'application/json';
        $adress = "text=" . urlencode($text) . "";
        $sparqlURL = "http://api.dbpedia-spotlight.org/pt/annotate?";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $sparqlURL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $adress);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: ' . $format));
        $answer = curl_exec($curl);

        curl_close($curl);

        if (isset(json_decode($answer, true)["Resources"])) {
            foreach (json_decode($answer, true)["Resources"] as $type) {
                if (strpos($type["@types"], "DBpedia:City")) {
                    return $type["@URI"];
                    break;
                }
            }
        }

        return null;
    }
}