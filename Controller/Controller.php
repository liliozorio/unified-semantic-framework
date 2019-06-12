<?php
require __DIR__ . '/../model/Pessoa.php';
require __DIR__ . '/../model/SelectTask.php';
require __DIR__ . '/../model/ScrapingModel.php';
require __DIR__ . '/../model/MethodModel.php';

require __DIR__ . '/../export_import/Allegrograph.php';

require __DIR__ . '/../tasks/LowerCaseNormalizerTask.php';
require __DIR__ . '/../tasks/CityCanonicaName.php';
require __DIR__ . '/../tasks/DBPediaSpotlightAnnotation.php';
require __DIR__ . '/../tasks/CompoundTask.php';
require __DIR__ . '/../tasks/AgeTask.php';
require __DIR__ . '/../tasks/ReturnAttributeTask.php';

require __DIR__ . '/../method/GetIdadeByDtNascimentoMethod.php';
require __DIR__ . '/../method/GetDtNascimentoByIdadeMethod.php';
require __DIR__ . '/../method/IBGELinearRegressionMethod.php';

require __DIR__ . '/../scraping/PoliciaCivilGO.php';
require __DIR__ . '/../scraping/PoliciaMilitarSC.php';

use model\Pessoa;
use model\SelectTask;
use model\ScrapingModel;
use export_import\Allegrograph;

class Controller
{
    /**
     * model que possui a função que seleciona task que deve ser instanciada
     */
    protected $selectTask;

    public function index()
    {
        $arr_scrapping = array();
        $arquivo = fopen('scraping.txt', 'r');
        while (!feof($arquivo)) {
            $linha = fgets($arquivo, 1024);
            $arr_scrapping[] = explode("\n",$linha)[0];
        }
        fclose($arquivo);
        $this->scraping($arr_scrapping);
        $this->createPessoas($arr_scrapping);
    }

    public function scraping($arr_arquivoScraping)
    {
        foreach ($arr_arquivoScraping as $arquivoScraping) {
            $scrapingClass = new ScrapingModel($arquivoScraping);
            $scrapingClass->scraping();
            $scrapingClass->start();
        }
    }

    public function createPessoas($arr_scrapping)
    {
        foreach ($arr_scrapping as $scrapping) {
            /** aquivo json com as configurações de tarefas e vocabulario dos atributos coletados*/
            $arquivoConfig = file_get_contents(__DIR__ . '/../json/' . $scrapping . '/config.json');
            $jsonConfig = json_decode($arquivoConfig);
            $selectTask = new SelectTask($jsonConfig);
            $bd = new Allegrograph();
            $contador = 0;

            $pasta = __DIR__ . '/../json/' . $scrapping . '/';
            $arquivos = glob("$pasta{*.json}", GLOB_BRACE);
            $numPessoas = count($arquivos);

            while ($contador < $numPessoas - 1) {
                /** aquivos json com dos atributos coletados*/
                $arquivoScraping = file_get_contents(__DIR__ . '/../json/' . $scrapping . '/' . $scrapping . '_' . $contador . '.json');
                $jsonScraping = json_decode($arquivoScraping);
                $this->executeTasks($selectTask, $jsonScraping, $bd);
                $contador++;
            }
        }
    }

    public function executeTasks($selectTask, $jsonScraping, $bd)
    {
        $pessoa = new Pessoa();
        foreach ($jsonScraping->attributes as $objAttribute) {

            foreach ($objAttribute as $attribute => $valueAttribute) {
                $attributeProcessed = $selectTask->getAttributeProcessed($valueAttribute, $attribute);
                $pessoa->setAttribute($attribute, $attributeProcessed);

            }
        }

        $selectTask->executeMethods($pessoa);

        $bd->insertPessoa($pessoa);
    }
}
