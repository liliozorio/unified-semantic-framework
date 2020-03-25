<?php

namespace scraping;

require_once __DIR__ . '/../simplehtmldom_1_8_1/simple_html_dom.php';

require_once __DIR__ . '/../Controller/../scraping/Scraping.php';

class PoliciaCivilGO implements Scraping
{
    private $nome;
    private $dt_nascimento;
    private $imagem;
    private $sexo;
    private $cidade;
    private $estado;
    private $dt_desaparecimento;
    private $fonte;
    private $data_localizacao;
    private $boletimDeOcorrecia;
    private $situacao;
    private $cont;

    public function scraping()
    {
        $urlBase = "http://www.policiacivil.go.gov.br/pessoas-desaparecidas/page/";

        $cont = 0;
        for ($page = 1; $page < 3; $page++) {
            $htmlPagina = file_get_html($urlBase . $page);
            print_r($urlBase . $page);

            $divsDesaparecidos = $htmlPagina->find('div[class="td-module-thumb"]');

            foreach ($divsDesaparecidos as $divDesaparecido) {

                $divDesaparecido = str_get_html($divDesaparecido);
                $linkDesaparecido = $divDesaparecido->find('a[rel="bookmark"]')[0];
                $pageDesaparecido = file_get_html($linkDesaparecido->href);

                $img = $pageDesaparecido->find('a img');
                if (isset($img[8])) {
                    $this->imagem = trim($img[8]->src);
                }

                $this->situacao = "desaparecida";
                $this->fonte = $linkDesaparecido->href;
                $auxNome = $pageDesaparecido->find('h1[class="entry-title"]');
                $this->nome = html_entity_decode(trim($auxNome[0]->plaintext));

                $divDadosDesaparecido = $pageDesaparecido->find('div[class="td-post-content"]');
                $divDadosDesaparecido = str_get_html($divDadosDesaparecido[0]);
                $pDadosDesaparecido = $divDadosDesaparecido->find('p');


                if (!empty($pDadosDesaparecido)) {
                    $dados = $pDadosDesaparecido[0]->plaintext;
                    $dados = explode("\n", $dados);
                    $this->getInformationDesaparecidos($dados);
                }
                $this->cont = $cont;
                $this->generateJson();
                $cont++;
            }
        }
        echo "<h4>Scraping Realizado</h4>";
    }

    public function getInformationDesaparecidos($dados)
    {
        foreach ($dados as $arr_informacoes) {
            $information = explode(":", $arr_informacoes);

            if (strtolower($information[0]) == "sexo") {
                $this->sexo = $information[1];
            }

            if (strtolower($information[0]) == "data de nascimento") {
                $this->dt_nascimento = $information[1];
            }

            if (strtolower($information[0]) == "data do desaparecimento") {
                $this->dt_desaparecimento = $information[1];
            }

            if (strtolower($information[0]) == "data do registro") {
                $this->data_localizacao = $information[1];
            }

            if (strtolower($information[0]) == "boletim de ocorrência") {
                $this->boletimDeOcorrecia = $information[1];
            }

            if (strtolower($information[0]) == "natural") {
                $information = explode("\\", $information[1]);
                $this->cidade = $information[0];
                $this->estado = $information[1];
            }
        }
    }

    public function generateJson($name = "")
    {
        $arr_json = array(
            'name' => 'PoliciaCivilGO',
            'attributes' => array(
                array('nome' => $this->nome),
                array('sexo' => $this->sexo),
                array('dt_nascimento' => $this->dt_nascimento),
                array('dt_desaparecimento' => $this->dt_desaparecimento),
                array('data_localizacao' => $this->data_localizacao),
                array('boletimDeOcorrecia' => $this->boletimDeOcorrecia),
                array('cidade' => $this->cidade),
                array('estado' => $this->estado),
                array('imagem' => $this->imagem),
                array('fonte' => $this->fonte),
                array('situacao' => $this->situacao),
            )
        );

        //format the data
        $formattedData = json_encode($arr_json);

        //set the filename
        $filename = 'PoliciaCivilGO_' . $this->cont . '.json';

        //open or create the file
        $handle = fopen(__DIR__ . '/../json/PoliciaCivilGO/' . $filename, 'w+');

        //write the data into the file
        fwrite($handle, $formattedData);

        //close the file
        fclose($handle);
    }
}
