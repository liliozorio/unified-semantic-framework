<?php

namespace scraping;

require_once __DIR__ . '/../simplehtmldom_1_8_1/simple_html_dom.php';

require_once 'Scraping.php';

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
        $urlBase = "http://www.policiacivil.go.gov.br/pessoas-desaparecidas/";

        $htmlPagina = file_get_html($urlBase);

        $divsDesaparecidos = $htmlPagina->find('div[class="td-module-thumb"]');

        $cont = 0;
        foreach ($divsDesaparecidos as $divDesaparecido) {

            $divDesaparecido = str_get_html($divDesaparecido);
            $linkDesaparecido = $divDesaparecido->find('a[rel="bookmark"]')[0];
            $paginaDesaparecido = file_get_html($linkDesaparecido->href);

            $img = $paginaDesaparecido->find('a img');
            if (isset($img[8])) {
                $this->imagem = trim($img[8]->src);
            }

            $this->situacao = "desaparecida";
            $this->fonte = $linkDesaparecido->href;
            $auxNome = $paginaDesaparecido->find('h1[class="entry-title"]');
            $this->nome = html_entity_decode(trim($auxNome[0]->plaintext));

            $divDadosDesaparecido = $paginaDesaparecido->find('div[class="td-post-content"]');
            $divDadosDesaparecido = str_get_html($divDadosDesaparecido[0]);
            $pDadosDesaparecido = $divDadosDesaparecido->find('p');


            if (!empty($pDadosDesaparecido)) {
                $dados = $pDadosDesaparecido[0]->plaintext;
                $dados = explode("\n", $dados);
                $this->getInformacoesDesaparecidos($dados);
            }
            $this->cont = $cont;
            $this->geraJson();
            $cont++;
        }
        echo "<h4>Scraping Realizado</h4>";
    }

    public function getInformacoesDesaparecidos($dados){
        foreach ($dados as $arr_informacoes) {
            $informacao = explode(":", $arr_informacoes);

            if (strtolower($informacao[0]) == "sexo") {
                $this->sexo = $informacao[1];
            }

            if (strtolower($informacao[0]) == "data de nascimento") {
                $this->dt_nascimento = $informacao[1];
            }

            if (strtolower($informacao[0]) == "data do desaparecimento") {
                $this->dt_desaparecimento = $informacao[1];
            }

            if (strtolower($informacao[0]) == "data do registro") {
                $this->data_localizacao = $informacao[1];
            }

            if (strtolower($informacao[0]) == "boletim de ocorrência") {
                $this->boletimDeOcorrecia = $informacao[1];
            }

            if (strtolower($informacao[0]) == "natural") {
                $informacao = explode("\\", $informacao[1]);
                $this->cidade = $informacao[0];
                $this->estado = $informacao[1];
            }
        }
    }

    public function geraJson($name = "")
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
        $filename = 'PoliciaCivilGO_'.$this->cont.'.json';

        //open or create the file
        $handle = fopen( __DIR__ . '/../json/PoliciaCivilGO/'.$filename, 'w+');

        //write the data into the file
        fwrite($handle, $formattedData);

        //close the file
        fclose($handle);
    }
}
