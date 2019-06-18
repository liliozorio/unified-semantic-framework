<?php
require __DIR__ . '/../model/Pessoa.php';
require __DIR__ . '/../model/SelectTask.php';
require __DIR__ . '/../model/ScrapingModel.php';
require __DIR__ . '/../model/MethodModel.php';
require __DIR__ . '/../tasks/CompoundTask.php';

use model\Pessoa;
use model\SelectTask;
use model\ScrapingModel;

class Controller
{
    /**
     * model que possui a função que seleciona task que deve ser instanciada
     */
    protected $selectTask;

    public function index()
    {
        $arr_scraping = array();
        $arquivo = fopen('scraping.txt', 'r');
        while (!feof($arquivo)) {
            $linha = fgets($arquivo, 1024);
            $scraping = explode("\n",$linha)[0];
            $arr_scraping[] = $scraping;
            eval('?>' . file_get_contents( __DIR__ . '/../scraping/'.$scraping.'.php'));
        }
        fclose($arquivo);
        $this->scraping($arr_scraping);
        $this->createPessoas($arr_scraping);
    }

    public function scraping($arr_arquivoScraping)
    {
        foreach ($arr_arquivoScraping as $arquivoScraping) {
            $scrapingClass = new ScrapingModel($arquivoScraping);
            $scrapingClass->scraping();
        }
    }

    public function createPessoas($arr_scraping)
    {
        foreach ($arr_scraping as $scraping) {
            echo '<h4>Scraping: '.$scraping.'</h4>';
            /** aquivo json com as configurações de tarefas e vocabulario dos atributos coletados*/
            $arquivoConfig = file_get_contents(__DIR__ . '/../json/' . $scraping . '/config.json');
            $jsonConfig = json_decode($arquivoConfig);
            $selectTask = new SelectTask($jsonConfig);
            $this->includeTasksMethods($selectTask);

            $pasta = __DIR__ . '/../json/' . $scraping . '/';
            $arquivos = glob("$pasta{*.json}", GLOB_BRACE);
            $numPessoas = count($arquivos);
	    $contador = 0;
            while ($contador < $numPessoas - 1) {
                /** aquivos json com dos atributos coletados*/
                $arquivoScraping = file_get_contents(__DIR__ . '/../json/' . $scraping . '/' . $scraping . '_' . $contador . '.json');
                $jsonScraping = json_decode($arquivoScraping);
                $this->executeTasks($selectTask, $jsonScraping);
                $contador++;
            }
        }
    }

    public function includeTasksMethods($selectTask){
        $arr_task = $selectTask->getArrTasks();
        foreach ($arr_task as $task){
          require_once (__DIR__ . '/../tasks/'.$task.'.php');
        }

        $arr_method = $selectTask->getArrMethods();
        foreach ($arr_method as $method){
            require_once (__DIR__ . '/../method/'.$method.'.php');
        }
    }

    public function executeTasks($selectTask, $jsonScraping)
    {
        $pessoa = new Pessoa();
        foreach ($jsonScraping->attributes as $objAttribute) {
            foreach ($objAttribute as $attribute => $valueAttribute) {
                $attributeProcessed = $selectTask->getAttributeProcessed($valueAttribute, $attribute);
                $pessoa->setAttribute($attribute, $attributeProcessed);
            }
        }

        $selectTask->executeMethods($pessoa);

        $this->insertPessoa($pessoa);
    }

    public function insertPessoa($pessoa)
    {
        $arr_exportClass = array();
        $arquivo = fopen('export.txt', 'r');
        while (!feof($arquivo)) {
            $linha = fgets($arquivo, 1024);
            $exportClass = explode("\n",$linha)[0];
            $arr_exportClass[] = $exportClass;
            require_once( __DIR__ . '/../export_import/'.$exportClass.'.php');
        }
        fclose($arquivo);

        foreach ($arr_exportClass as $exportClass){
            try {
                $bd = new ReflectionClass('export_import\\'.$exportClass);
                $bd = $bd->newInstance();
            } catch (\ReflectionException $e) {
                echo("<b>Erro:</b> ". $e->getMessage());
            }

            $bd->importPessoa($pessoa);
        }
    }

}
