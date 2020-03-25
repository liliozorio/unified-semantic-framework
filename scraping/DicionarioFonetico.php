<?php

namespace scraping;

use ArrayObject;

require_once __DIR__ . '/../simplehtmldom_1_8_1/simple_html_dom.php';

require_once __DIR__.'/../Controller/../scraping/Scraping.php';

class DicionarioFonetico implements Scraping
{
    private $letras = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    public function scraping()
    {

        $urlBase = "http://www.portaldalinguaportuguesa.org/index.php?action=fonetica&region=spx&act=list&letter=";
        $letraAtual = "x";
        $numeroAtual = 0;
        //$htmlPagina = file_get_html($urlBase.$letraAtual."&start=".$numeroAtual);
        //echo $palavrasPag[0];
        
        //Tenho que usar o getElementByTagName para percorrer por todos os tds da tabela

        //Para a letra X tem 77 resultados, cada página vai de 20 em 20, o que dá 4 páginas:
        $flag = true;
        $i = 0;
        while($flag) {
            $numeroAtual = $i*20;
            $htmlPagina = file_get_html($urlBase.$letraAtual."&start=".$numeroAtual);
            $palavrasPag = $htmlPagina->find('td[title="Palavra"]');
            $foneticasPag = $htmlPagina->find('td[title="Fonética"]');
            $tabelaPag = $htmlPagina->find('table[id="rollovertable"]');
            $categoria = $tabelaPag[0]->getElementsByTagName('td');
            $indexCategoria = 1;
            if (is_null($palavrasPag[0]))
            {
                echo "bagulho é doido se chegou aqui";
                $flag = false;
            }
            else
            {
                for($j = 0; $j < 20; $j++)
                {
                    echo ($palavrasPag[$j]->plaintext).' -> '.($categoria[$indexCategoria])."->".($foneticasPag[$j]->plaintext)."<br>";
                    $indexCategoria = $indexCategoria + 3;
                }   
            }
            /*if($i === 1)
            {
                $flag=false; z4Xt4V9pDBaG
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