<?php

namespace tasks;

require_once 'Task.php';

class DBPediaSpotlightAnnotation implements Task
{
    public function processing($attribute)
    {
        $texto = $attribute;
        $format = 'application/json';
        $endereco = "text=" . urlencode($texto) . "&confidence=0&types=DBpedia%3APlace";
        $sparqlURL = "http://api.dbpedia-spotlight.org/pt/annotate?";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $sparqlURL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $endereco);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: ' . $format));
        $resposta = curl_exec($curl);


        //SSL certificate problem: certificate has expired
        //$error_message = curl_error($errno);

        curl_close($curl);


        if (isset(json_decode($resposta, true)["Resources"])) {
            foreach (json_decode($resposta, true)["Resources"] as $type) {
                if (strpos($type["@types"], "DBpedia:City")) {
                    return $type["@surfaceForm"];
                    break;
                }
            }
        }

        return null;
    }

}
