<?php

namespace scraping;

use ArrayObject;

require_once __DIR__ . '/../simplehtmldom_1_8_1/simple_html_dom.php';

require_once __DIR__.'/../Controller/../scraping/Scraping.php';

class DicionarioFonetico implements Scraping
{

    public function scraping()
    {

        $urlBase = "http://www.portaldalinguaportuguesa.org/index.php?action=fonetica&region=spx&act=list&letter=";
        $letraAtual = "u";
        $numeroAtual = 0;
        //$htmlPagina = file_get_html($urlBase.$letraAtual."&start=".$numeroAtual);
        //echo $palavrasPag[0];
        //Para a letra A tem 7283 resultados, cada página vai de 20 em 20, o que dá 364 páginas: 
        $flag = true;
        $i = 0;
        while($flag) {
            $numeroAtual = $i*20;
            $htmlPagina = file_get_html($urlBase.$letraAtual."&start=".$numeroAtual);
            $palavrasPag = $htmlPagina->find('td[title="Palavra"]');
            for($j = 0; $j < 20; $j++)
            {
                echo ($palavrasPag[$j]->plaintext).', ';//Testar variações
            }
            $flag=false;
            /*if (end($palavrasPag) == null){
                $flag = false;
            }*/
            $i++;
        }
        echo "<h4>Scraping Realizado</h4>";
    }

    public function getInformation($dados)
    {
        $information = explode(":", $dados);

        if (strtolower($information[0]) == "nome") {
            $this->sexo = $information[1];
        }

        if (strtolower($information[0]) == "data de nascimento") {
            $this->dt_nascimento = $information[1];
        }

        if (strtolower($information[0]) == "desaparecido em") {
            $this->dt_desaparecimento = $information[1];
        }

        if (strtolower($information[0]) == "estado onde reside") {
            $this->estado = $information[1];
        }

        if (strtolower($information[0]) == "cidade onde reside") {
            $this->cidade = $information[1];
        }

        if (strtolower($information[0]) == "descrição dos fatos") {
            $this->circunstancia_desaparecimento = $information[1];
        }
    }

    public function generateJson($name)
    {
        $arr_json = array(
            'name' => 'PoliciaMilitarSC',
            'attributes' => array(
                array('nome' => $this->nome),
                array('dt_nascimento' => $this->dt_nascimento),
                array('dt_desaparecimento' => $this->dt_desaparecimento),
                array('cidade' => $this->cidade),
                array('estado' => $this->estado),
                array('imagem' => $this->imagem),
                array('fonte' => $this->fonte),
                array('circunstancia_desaparecimento' => $this->circunstancia_desaparecimento),
                array('situacao' => $this->situacao),
            )
        );

        //format the data
        $formattedData = json_encode($arr_json);

        //set the filename
        $filename = $name;

        //open or create the file
        $handle = fopen( __DIR__ . '/../json/PoliciaMilitarSC/'.$filename, 'w+');

        //write the data into the file
        fwrite($handle, $formattedData);

        //close the file
        fclose($handle);
    }
}

?>