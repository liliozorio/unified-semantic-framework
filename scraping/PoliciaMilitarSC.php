<?php

namespace scraping;

require_once __DIR__ . '/../simplehtmldom_1_8_1/simple_html_dom.php';

require_once 'Scraping.php';

class PoliciaMilitarSC implements Scraping
{
    private $nome;
    private $dt_nascimento;
    private $imagem;
    private $cidade;
    private $estado;
    private $dt_desaparecimento;
    private $fonte;
    private $circunstancia_desaparecimento;
    private $situacao;


    public function scraping()
    {
        $cont = 0;

        $urlBase = "http://www.pm.sc.gov.br/desaparecidos/consulta-desaparecidos.php?&p_init=";

        for ($i = 0; $i <= 20; $i++) {
            $htmlPagina = file_get_html($urlBase . $i);

            foreach ($htmlPagina->find('div[class="item"]') as $item) {
                $cont++;

                $img = $item->find('img[width="75px"]');
                if (isset($img[0])) {
                    $this->imagem = "http://www.pm.sc.gov.br" . $img[0]->src;
                }

                $this->fonte = "http://www.pm.sc.gov.br/desaparecidos/consulta-desaparecidos.php?&p_init=$i";
                $this->situacao = "desaparecida";

                foreach ($item->find('div[class="item-info-detail"]') as $itemInfomacao) {
                    $this->getInformacoesDesaparecidos($itemInfomacao->plaintext);
                }
                $name = $name = 'PoliciaMilitarSC_'.$cont.'.json';
                $this->geraJson($name);
            }
        }
        echo "<h4>Scraping Realizado</h4>";
    }

    public function getInformacoesDesaparecidos($dados)
    {
        $informacao = explode(":", $dados);

        if (strtolower($informacao[0]) == "nome") {
            $this->sexo = $informacao[1];
        }

        if (strtolower($informacao[0]) == "data de nascimento") {
            $this->dt_nascimento = $informacao[1];
        }

        if (strtolower($informacao[0]) == "desaparecido em") {
            $this->dt_desaparecimento = $informacao[1];
        }

        if (strtolower($informacao[0]) == "estado onde reside") {
            $this->estado = $informacao[1];
        }

        if (strtolower($informacao[0]) == "cidade onde reside") {
            $this->cidade = $informacao[1];
        }

        if (strtolower($informacao[0]) == "descrição dos fatos") {
            $this->circunstancia_desaparecimento = $informacao[1];
        }
    }

    public function geraJson($name)
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


    public function printArray($array)
    {
        print "<pre>";
        print_r($array);
        print "<pre>";
        die();
    }
}