<?php
require __DIR__ . '/../model/Pessoa.php';
require __DIR__ . '/../model/Allegrograph.php';
require __DIR__ . '/../model/SelectTask.php';
require __DIR__ . '/../model/ScrapingModel.php';
require __DIR__ . '/../tasks/LowerCaseNormalizerTask.php';
require __DIR__ . '/../tasks/IBGELinearRegression.php';
require __DIR__ . '/../tasks/CityCanonicaName.php';
require __DIR__ . '/../tasks/DBPediaSpotlightAnnotation.php';
require __DIR__ . '/../tasks/CompoundTask.php';
require __DIR__ . '/../tasks/AgeTask.php';
require __DIR__ . '/../tasks/ReturnAttributeTask.php';
require __DIR__ . '/../scraping/PoliciaCivilGO.php';
require __DIR__ . '/../scraping/PoliciaMilitarSC.php';

use model\Pessoa;
use model\SelectTask;
use tasks\AgeTask;
use tasks\IBGELinearRegression;
use model\Allegrograph;
use model\ScrapingModel;

class DefaultController
{
    /**
     * model que possui a função que seleciona task que deve ser instanciada
     */
    protected $selectTask;

    public function index()
    {
        $arr_scrapping = array('PoliciaCivilGO', 'PoliciaMilitarSC');
        $this->scraping($arr_scrapping);
        $this->createPessoas();
    }

    public function createPessoas()
    {

        /** aquivo json com as configurações de tarefas e vocabulario dos atributos coletados*/
        $arquivoConfig = file_get_contents(__DIR__ . '/../json/config.json');
        $jsonConfig = json_decode($arquivoConfig);
        $selectTask = new SelectTask($jsonConfig);
        $bd = new Allegrograph();
        $contador = 0;
        while($contador < 11){
            /** aquivos json com dos atributos coletados do site da PoliviCivilGO*/
            $arquivoScraping = file_get_contents(__DIR__ . '/../json/PoliciaCivilGO/PoliciaCivilGO_'.$contador.'.json');
            $jsonScraping = json_decode($arquivoScraping);
            $this->executeTasks($selectTask,$jsonScraping, $bd);
            $contador++;
        }

        $contador = 1;
        while($contador < 11){
            /** aquivos json com dos atributos coletados do site da PoliviCivilGO*/
            $arquivoScraping = file_get_contents(__DIR__ . '/../json/PoliciaMilitarSC/PoliciaMilitarSC_'.$contador.'.json');
            $jsonScraping = json_decode($arquivoScraping);
            $this->executeTasks($selectTask,$jsonScraping, $bd);
            $contador++;
        }
    }

    public function executeTasks($selectTask, $jsonScraping, $bd){
        $pessoa = new Pessoa();
        foreach ($jsonScraping->attributes as $objAttribute) {

            foreach ($objAttribute as $attribute => $valueAttribute) {
                $attributeProcessed = $selectTask->getAttributeProcessed($valueAttribute, $attribute);
                $pessoa->setAttribute($attribute, $attributeProcessed);

            }
        }

        if($pessoa->getAttribute('idade') == null && $pessoa->getAttribute('dt_nascimento') != null){
            $ageTask = new AgeTask();
            $idade = $ageTask->getIdadeByDtNascimento($pessoa->getAttribute('dt_nascimento'));
            $pessoa->setAttribute('idade',$idade);
        }

        if($pessoa->getAttribute('dt_nascimento') == null && $pessoa->getAttribute('idade') != null){
            $ageTask = new AgeTask();
            $dt_nascimento = $ageTask->getDtNascimentoByIdade($pessoa->getAttribute('idade'));
            $pessoa->setAttribute('dt_nascimento',$dt_nascimento);
        }

        if($pessoa->getAttribute('sexo') == null && $pessoa->getAttribute('nome') != null){
            $IBGELinearRegression = new IBGELinearRegression();
            $sexo = $IBGELinearRegression->processing($pessoa->getAttribute('nome'));
            $pessoa->setAttribute('sexo',$sexo);
        }

        $bd->insertPessoa($pessoa);
    }

    public function scraping($arr_arquivoScraping){
        foreach ($arr_arquivoScraping as $arquivoScraping){
            $scrapingClass = new ScrapingModel($arquivoScraping);
            $scrapingClass->scraping();
        }
    }
}
