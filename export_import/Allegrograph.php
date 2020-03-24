<?php

namespace export_import;
require_once __DIR__.'/../export_import/Export_Import.php';

class Allegrograph implements Export_Import
{
    public function import($person)
    {

    }

    public function export($person)
    {
        $this->insertPerson($person);
    }


    public function insertPerson($person)
    {
        $Newid = $this->gethigherIdDesaparecido();
        $Newid++;

        $login = "admin:admin";
        $format = "application/sparql-results+json";

        $prefix = "<http://www.desaparecidos.ufjf.br/desaparecidos/" . $Newid . ">";
        $address = "PREFIX foaf:<http://xmlns.com/foaf/0.1/>
					 PREFIX des:<http://www.desaparecidos.com.br/rdf/>
					 PREFIX dbpprop:<http://dbpedia.org/property/> 
					 INSERT DATA {" . $prefix . " des:id \"" . $Newid . "\".
                                  " . $prefix . " foaf:name \"" . $person->getAttribute('nome') . "\".";

        $address = $address . $prefix . " foaf:nick \"" . $person->getAttribute('apelido') . "\".";
        $address = $address . $prefix . " foaf:birthday \"" . $person->getAttribute('dt_nascimento') . "\".";
        $address = $address . $prefix . " foaf:gender \"" . $person->getAttribute('sexo') . "\".";
        $address = $address . $prefix . " foaf:img \"" . $person->getAttribute('imagem'). "\".";
        $address = $address . $prefix . " foaf:age \"" . $person->getAttribute('idade') . "\".";
        $address = $address . $prefix . " des:cityDes \"" . $person->getAttribute('cidade') . "\".";
        $address = $address . $prefix . " des:stateDes \"" . $person->getAttribute('estado') . "\".";
        $address = $address . $prefix . " dbpprop:height \"" . $person->getAttribute('altura') . "\".";
        $address = $address . $prefix . " dbpprop:weight \"" . $person->getAttribute('peso') . "\".";
        $address = $address . $prefix . " des:skin \"" . $person->getAttribute('pele') . "\".";
        $address = $address . $prefix . " dbpprop:hairColor \"" . $person->getAttribute('cor_cabelo') . "\".";
        $address = $address . $prefix . " dbpprop:eyeColor \"" . $person->getAttribute('cor_olho') . "\".";
        $address = $address . $prefix . " des:moreCharacteristics \"" . $person->getAttribute('mais_caracteristicas') . "\".";
        $address = $address . $prefix . " des:disappearanceDate \"" . $person->getAttribute('dt_desaparecimento') . "\".";
        $address = $address . $prefix . " des:disappearancePlace \"" . $person->getAttribute('local_desaparecimento') . "\".";
        $address = $address . $prefix . " des:circumstanceLocation \"" . $person->getAttribute('circunstancia_desaparecimento') . "\".";
        $address = $address . $prefix . " des:dateLocation \"" . $person->getAttribute('data_localizacao') . "\".";
        $address = $address . $prefix . " des:additionalData \"" . $person->getAttribute('dados_adicionais') . "\".";
        $address = $address . $prefix . " des:status \"" . $person->getAttribute('situacao') . "\".";
        $address = $address . $prefix . " des:source \"" . $person->getAttribute('fonte') . "\". }";

        $url = urlencode($address);
        $sparqlURL = 'http://localhost:10035/repositories/Teste_Insert?query=' . $url . '';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, $login);
        curl_setopt($curl, CURLOPT_URL, $sparqlURL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: ' . $format));
        curl_exec($curl);

        curl_close($curl);
    }

    function gethigherIdDesaparecido()
    {
        $format = "application/sparql-results+json";
        $login = "admin:admin"; // login:senha
        $BD = "http://localhost:10035/repositories/Teste_Insert";

        $query = "PREFIX foaf:<http://xmlns.com/foaf/0.1/>
                             PREFIX des:<http://www.desaparecidos.com.br/rdf/>  
			     PREFIX dbpprop:<http://dbpedia.org/property/>
                             select ?x where{ ?id des:id ?x} order by desc(xsd:int(?x)) limit 1";

        $url = urlencode($query);
        $sparqlURL = $BD . '?query=' . $url;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $sparqlURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $login);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: " . $format));

        $resposta = curl_exec($curl);

        curl_close($curl);

        $jsonfile = json_decode($resposta);
        $jsonfile = $jsonfile->results;

        if(empty($jsonfile->bindings))
            return 1;

        $jsonfile = $jsonfile->bindings[0];
        $jsonfile = $jsonfile->x;
        $jsonfile = $jsonfile->value;

        $id = (int)$jsonfile;
        return $id;
    }

}