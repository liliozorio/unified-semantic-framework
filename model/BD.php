<?php


class BD
{
    public function conexao()
    {
        $format = "application/sparql-results+json";
        $login = "admin:admin"; // login:senha
        $BD1 = "http://localhost:10035/repositories/TESTE";

        $query = "# View triples
                    SELECT ?tenancy ?x{
                      ?tenancy  <http://www.desaparecidos.com.br/rdf/moreCharacteristics> ?x .
                      OPTIONAL { ?tenancy <http://www.desaparecidos.com.br/rdf/cityDes> ?o } .
                      FILTER ( !bound(?o) )
                    }
                    
                    ORDER BY ?tenancy";

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
        var_dump($json);


    }

    public function insertPessoa($pessoa, $arr_AttributeTag)
    {
        $newid = $this->getMaiorIdDesaparecido();

        $format = "application/sparql-results+json";
        $login = "admin:admin"; // login:senha
        $BD1 = "http://localhost:10035/repositories/TESTE";

        $prefix = "<http://www.desaparecidos.ufjf.br/desaparecidos/" . $newid . ">";
        $query = "PREFIX foaf:<http://xmlns.com/foaf/0.1/>
					 PREFIX des:<http://www.desaparecidos.com.br/rdf/>
					 PREFIX dbpprop:<http://dbpedia.org/property/> 
					 INSERT DATA {" . $prefix . " des:id \"" . $newid . "\".
                                  " . $prefix . " foaf:name \"" . $p->nome . "\".";


//        $arr_attribute = array(
//            'name',
//            'gender',
//        );

        $query = $query . $prefix . $arr_AttributeTag[$attribute]."\"" . $pessoa->getNome() . "\".";
        $query = $query . $prefix . $arr_AttributeTag[$attribute]."\"" . $pessoa->getSexo() . "\".";


        $url = urlencode($query);

        $sparqlURL = $BD1. '?query=' . $url . '';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, $login);
        curl_setopt($curl, CURLOPT_URL, $sparqlURL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //Recebe o output da url como uma string
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: ' . $format));
        $resposta = curl_exec($curl);

        curl_close($curl);
        echo "resposta insert caso -1: " . $resposta . "<br>";
    }

    function getMaiorIdDesaparecido()
    {


        $format = "application/sparql-results+json";
        $login = "admin:admin"; // login:senha
        $BD1 = "http://localhost:10035/repositories/TESTE";

        $query = "PREFIX foaf:<http://xmlns.com/foaf/0.1/>
                             PREFIX des:<http://www.desaparecidos.com.br/rdf/>  
			     PREFIX dbpprop:<http://dbpedia.org/property/>
                             select ?x where{ ?id des:id ?x} order by desc(xsd:int(?x)) limit 1";

        $url = urlencode($query);
        $sparqlURL = $BD1 . '?query=' . $url;

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

        //fim da getMaiorID
    }

}