<?php

namespace model;

$login = "admin:admin"; // login:senha
$BD = "http://localhost:10035/repositories/desaparecidos6";

class BD
{
    public function conexao()
    {
        $format = "application/sparql-results+json";

        $login = "admin:admin"; // login:senha
        $BD1 = "http://127.0.0.1:10035/#/repositories/TESTE";
        $query = '
                     PREFIX dbpprop:<http://dbpedia.org/property/>
					 SELECT  ?nome ?apelido ?data_nascimento ?sexo ?imagem ?idade ?cidade ?estado ?altura ?peso ?pele ?cor_cabelo ?cor_olho ?mais_caracteristicas 
					 ?data_desaparecimento ?local_desaparecimento ?circunstancia_desaparecimento ?data_localizacao ?dados_adicionais ?status ?fonte 
					 WHERE {
					 OPTIONAL {?recurso foaf:name ?nome}.
					 OPTIONAL {?recurso foaf:nick ?apelido}.
					 OPTIONAL {?recurso foaf:birthday ?data_nascimento}.
					 OPTIONAL {?recurso foaf:gender ?sexo}.
					 OPTIONAL {?recurso foaf:img ?imagem}.
					 OPTIONAL {?recurso foaf:age ?idade}.
					 OPTIONAL {?recurso dbpprop:height ?altura}.
					 OPTIONAL {?recurso dbpprop:weight ?peso}.
					 OPTIONAL {?recurso dbpprop:hairColor ?cor_cabelo}.
					 OPTIONAL {?recurso dbpprop:eyeColor ?cor_olho}.
			
					} ';

        $url = urlencode($query);
        $sparqlURL = $BD1 . '?query=' . $url;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $sparqlURL);

        curl_setopt($curl, CURLOPT_USERPWD, $login);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //Recebe o output da url como uma string


        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: " . $format));
        $resposta = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($resposta);
        var_dump($resposta);

    }
}