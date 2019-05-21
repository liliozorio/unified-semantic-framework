<?php

namespace model;

require_once 'Export_Import.php';

class Allegrograph implements Export_Import
{
    public function exportPessoa($pessoa)
    {
    }

    public function importPessoa($pessoa)
    {
        $this->insertPessoa($pessoa);
    }


    public function insertPessoa($pessoa)
    {
        $Newid = $this->getMaiorIdDesaparecido();
        $Newid++;

        $login = "admin:admin";
        $format = "application/sparql-results+json";

        $prefix = "<http://www.desaparecidos.ufjf.br/desaparecidos/" . $Newid . ">";
        $endereco = "PREFIX foaf:<http://xmlns.com/foaf/0.1/>
					 PREFIX des:<http://www.desaparecidos.com.br/rdf/>
					 PREFIX dbpprop:<http://dbpedia.org/property/> 
					 INSERT DATA {" . $prefix . " des:id \"" . $Newid . "\".
                                  " . $prefix . " foaf:name \"" . $pessoa->getAttribute('nome') . "\".";

        $endereco = $endereco . $prefix . " foaf:nick \"" . $pessoa->getAttribute('apelido') . "\".";
        $endereco = $endereco . $prefix . " foaf:birthday \"" . $pessoa->getAttribute('dt_nascimento') . "\".";
        $endereco = $endereco . $prefix . " foaf:gender \"" . $pessoa->getAttribute('sexo') . "\".";
        $endereco = $endereco . $prefix . " foaf:img \"" . $pessoa->getAttribute('imagem'). "\".";
        $endereco = $endereco . $prefix . " foaf:age \"" . $pessoa->getAttribute('idade') . "\".";
        $endereco = $endereco . $prefix . " des:cityDes \"" . $pessoa->getAttribute('cidade') . "\".";
        $endereco = $endereco . $prefix . " des:stateDes \"" . $pessoa->getAttribute('estado') . "\".";
        $endereco = $endereco . $prefix . " dbpprop:height \"" . $pessoa->getAttribute('altura') . "\".";
        $endereco = $endereco . $prefix . " dbpprop:weight \"" . $pessoa->getAttribute('peso') . "\".";
        $endereco = $endereco . $prefix . " des:skin \"" . $pessoa->getAttribute('pele') . "\".";
        $endereco = $endereco . $prefix . " dbpprop:hairColor \"" . $pessoa->getAttribute('cor_cabelo') . "\".";
        $endereco = $endereco . $prefix . " dbpprop:eyeColor \"" . $pessoa->getAttribute('cor_olho') . "\".";
        $endereco = $endereco . $prefix . " des:moreCharacteristics \"" . $pessoa->getAttribute('mais_caracteristicas') . "\".";
        $endereco = $endereco . $prefix . " des:disappearanceDate \"" . $pessoa->getAttribute('dt_desaparecimento') . "\".";
        $endereco = $endereco . $prefix . " des:disappearancePlace \"" . $pessoa->getAttribute('local_desaparecimento') . "\".";
        $endereco = $endereco . $prefix . " des:circumstanceLocation \"" . $pessoa->getAttribute('circunstancia_desaparecimento') . "\".";
        $endereco = $endereco . $prefix . " des:dateLocation \"" . $pessoa->getAttribute('data_localizacao') . "\".";
        $endereco = $endereco . $prefix . " des:additionalData \"" . $pessoa->getAttribute('dados_adicionais') . "\".";
        $endereco = $endereco . $prefix . " des:status \"" . $pessoa->getAttribute('situacao') . "\".";
        $endereco = $endereco . $prefix . " des:source \"" . $pessoa->getAttribute('fonte') . "\". }";

        $url = urlencode($endereco);
        $sparqlURL = 'http://localhost:10035/repositories/Teste_Insert?query=' . $url . '';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, $login);
        curl_setopt($curl, CURLOPT_URL, $sparqlURL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: ' . $format));
        $resposta = curl_exec($curl);

        curl_close($curl);
        echo $resposta . "<br>";
    }

    function getMaiorIdDesaparecido()
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
        curl_setopt($curl, CURLOPT_USERPWD, $login);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: " . $format));

        $resposta = curl_exec($curl);

        curl_close($curl);

        $jsonfile = json_decode($resposta);
        $jsonfile = $jsonfile->results;
        $jsonfile = $jsonfile->bindings[0];
        $jsonfile = $jsonfile->x;
        $jsonfile = $jsonfile->value;

        $id = (int)$jsonfile;
        return $id;
    }

}