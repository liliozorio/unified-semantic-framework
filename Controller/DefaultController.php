<?php
require __DIR__ . '/../model/Pessoa.php';
require __DIR__ . '/../model/BD.php';
require __DIR__ . '/../model/SelectTask.php';
require __DIR__ . '/../tasks/LowerCaseNormalizerTask.php';
require __DIR__ . '/../tasks/IBGELinearRegression.php';
require __DIR__ . '/../tasks/CityCanonicaName.php';
require __DIR__ . '/../tasks/DBPediaSpotlightAnnotation.php';
require __DIR__ . '/../tasks/CompoundTask.php';
require __DIR__ . '/../tasks/AgeTask.php';


use model\Pessoa;
use model\SelectTask;

class DefaultController
{
    /**
     * model que possui a função que seleciona task que deve ser instanciada
     */
    protected $selectTask;

    public function index()
    {
        /** aquivo json com as configurações de tarefas e vocabulario dos atributos coletados*/
        $arquivoConfig = file_get_contents(__DIR__ . '/../json/config.json');
        $jsonConfig = json_decode($arquivoConfig);

        $selectTask = new \model\SelectTask($jsonConfig);

        $pessoa = $this->criaPessoa($selectTask);

        if($pessoa->getAttribute('idade') == null && $pessoa->getAttribute('dt_nascimento') != null){
            $ageTask = new \tasks\AgeTask();
            $idade = $ageTask->getIdadeByDtNascimento($pessoa->getAttribute('dt_nascimento'));
            $pessoa->setAttribute('idade',$idade);
        }

        if($pessoa->getAttribute('dt_nascimento') == null && $pessoa->getAttribute('idade') != null){
            $ageTask = new \tasks\AgeTask();
            $dt_nascimento = $ageTask->getDtNascimentoByIdade($pessoa->getAttribute('idade'));
            $pessoa->setAttribute('dt_nascimento',$dt_nascimento);
        }

        if($pessoa->getAttribute('sexo') == null && $pessoa->getAttribute('nome') != null){
            $IBGELinearRegression = new \tasks\IBGELinearRegression();
            $sexo = $IBGELinearRegression->processing($pessoa->getAttribute('nome'));
            $pessoa->setAttribute('sexo',$sexo);
        }

        $pessoa->printPessoa();
    }

    public function criaPessoa($selectTask)
    {
        /** aquivo json com dos atributos coletados*/
        $arquivoScraping = file_get_contents(__DIR__ . '/../json/teste.json');
        $jsonScraping = json_decode($arquivoScraping);

        $pessoa = new Pessoa();
        foreach ($jsonScraping->attributes as $objAttribute) {

            foreach ($objAttribute as $attribute => $valueAttribute) {
                $attributeProcessed = $selectTask->getAttributeProcessed($valueAttribute, $attribute);
                $pessoa->setAttribute($attribute, $attributeProcessed);

            }
        }

        return $pessoa;
    }

}
