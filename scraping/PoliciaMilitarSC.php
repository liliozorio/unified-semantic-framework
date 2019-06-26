<?php

namespace scraping;

require_once __DIR__ . '/../simplehtmldom_1_8_1/simple_html_dom.php';

require_once __DIR__.'/../Controller/../scraping/Scraping.php';

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
            $htmlPage = file_get_html($urlBase . $i);

            foreach ($htmlPage->find('div[class="item"]') as $item) {
                $img = $item->find('img[width="75px"]');
                if (isset($img[0])) {
                    $this->imagem = "http://www.pm.sc.gov.br" . $img[0]->src;
                }

                $this->fonte = "http://www.pm.sc.gov.br/desaparecidos/consulta-desaparecidos.php?&p_init=$i";
                $this->situacao = "desaparecida";

                foreach ($item->find('div[class="item-info-detail"]') as $itemInfomacao) {
                    $this->getInformationDesaparecidos($itemInfomacao->plaintext);
                }
                $name = $name = 'PoliciaMilitarSC_'.$cont.'.json';
                $this->generateJson($name);
                $cont++;
            }
        }
        echo "<h4>Scraping Realizado</h4>";
    }

    public function getInformationDesaparecidos($dados)
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