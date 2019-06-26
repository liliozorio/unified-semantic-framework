<?php
require __DIR__ . '/../model/Person.php';
require __DIR__ . '/../model/SelectTask.php';
require __DIR__ . '/../model/ScrapingModel.php';
require __DIR__ . '/../model/MethodModel.php';
require __DIR__ . '/../tasks/CompoundTask.php';

use model\Person;
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
        $archive = fopen('scraping.txt', 'r');
        while (!feof($archive)) {
            $row = fgets($archive, 1024);
            $scraping = explode("\n",$row)[0];
            $arr_scraping[] = $scraping;
            eval('?>' . file_get_contents( __DIR__ . '/../scraping/'.$scraping.'.php'));
        }
        fclose($archive);
        $this->scraping($arr_scraping);
        $this->createPeople($arr_scraping);
    }

    public function scraping($arr_archiveScraping)
    {
        foreach ($arr_archiveScraping as $archiveScraping) {
            $scrapingClass = new ScrapingModel($archiveScraping);
            $scrapingClass->scraping();
        }
    }

    public function createPeople($arr_scraping)
    {
        foreach ($arr_scraping as $scraping) {
            echo '<h4>Create People: '.$scraping.'</h4>';
            /** aquivo json com as configurações de tarefas e vocabulario dos atributos coletados*/
            $archiveConfig = file_get_contents(__DIR__ . '/../json/' . $scraping . '/config.json');
            $jsonConfig = json_decode($archiveConfig);
            $selectTask = new SelectTask($jsonConfig);
            $this->includeTasksMethods($selectTask);
            $counter = 0;

            $pasta = __DIR__ . '/../json/' . $scraping . '/';
            $archives = glob("$pasta{*.json}", GLOB_BRACE);
            $numPeople = count($archives);

            while ($counter < $numPeople - 1) {
                /** aquivos json com dos atributos coletados*/
                $archiveScraping = file_get_contents(__DIR__ . '/../json/' . $scraping . '/' . $scraping . '_' . $counter . '.json');
                $jsonScraping = json_decode($archiveScraping);
                $this->executeTasks($selectTask, $jsonScraping);
                $counter++;
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
        $person = new Person();
        foreach ($jsonScraping->attributes as $objAttribute) {
            foreach ($objAttribute as $attribute => $valueAttribute) {
                $attributeProcessed = $selectTask->getAttributeProcessed($valueAttribute, $attribute);
                $person->setAttribute($attribute, $attributeProcessed);
            }
        }

        $selectTask->executeMethods($person);

        $this->insertPerson($person);
    }

    public function insertPerson($person)
    {
        $arr_exportClass = array();
        $archive = fopen('export.txt', 'r');
        while (!feof($archive)) {
            $row = fgets($archive, 1024);
            $exportClass = explode("\n",$row)[0];
            $arr_exportClass[] = $exportClass;
            require_once( __DIR__ . '/../export_import/'.$exportClass.'.php');
        }
        fclose($archive);

        foreach ($arr_exportClass as $exportClass){
            try {
                $bd = new ReflectionClass('export_import\\'.$exportClass);
                $bd = $bd->newInstance();
            } catch (\ReflectionException $e) {
                echo("<b>Erro:</b> ". $e->getMessage());
            }

            $bd->import($person);
        }
    }

}
