<?php
require __DIR__. '/../model/Pessoa.php';
require __DIR__ . '/../tasks/LowerCaseNormalizerTask.php';
require __DIR__ . '/../tasks/GenderTask.php';
require __DIR__ . '/../tasks/CityTask.php';
require __DIR__ . '/../tasks/CityPrepareTask.php';

use model\Pessoa;
use tasks\GenderTask;
use tasks\CityTask;
use tasks\LowerCaseNormalizerTask;

class DefaultController
{
    public function index()
    {
        /** aquivo json com as configurações de tarefas e vocabulario dos atributos coletados*/
        $arquivoConfig = file_get_contents(__DIR__ . '/../json/conf.json');
        $jsonConfig = json_decode($arquivoConfig);

        $arr_AttributeTask = $this->getArrayAttributeTask($jsonConfig);
        $pessoa = $this->criaPessoa($arr_AttributeTask);
        $pessoa->printPessoa();

    }

    public function selectTask($attribute, $attributeTask)
    {
        if ($attributeTask == 'LowerCaseNormalizer') {
            $task = new LowerCaseNormalizerTask();
            return $task->processing($attribute);
        }

        if ($attributeTask == 'GenderTask') {
            $task = new GenderTask();
            return $task->processing($attribute);
        }

        if ($attributeTask == 'CityTask') {
            $task = new CityTask();
            return $task->processing($attribute);
        }
    }

    public function criaPessoa($arr_AttributeTask)
    {
        /** aquivo json com dos atributos coletados*/
        $arquivoScraping = file_get_contents(__DIR__ . '/../json/teste.json');
        $jsonScraping = json_decode($arquivoScraping);

        $pessoa = new Pessoa();
        foreach ($jsonScraping->attributes as $name => $attribute) {
            if (isset($attribute->name)) {
                $name = $this->selectTask($attribute->name, $arr_AttributeTask['name']);
                $pessoa->setNome($name);
            }

            if (isset($attribute->sexo)) {
                $sexo = $this->selectTask($attribute->sexo, $arr_AttributeTask['sexo']);
                $pessoa->setSexo($sexo);
            }

            if (isset($attribute->cidade)) {
                $cidade = $this->selectTask($attribute->cidade, $arr_AttributeTask['cidade']);
                $pessoa->setCidade($cidade);
            }

//            if (isset($attribute->apelido)) {
//                $apelido = $this->selectTask($attribute->apelido, $arr_AttributeTask['apelido']);
//                $pessoa->setApelido($apelido);
//            }
//
//            if (isset($attribute->dt_nascimento)) {
//                $dt_nascimento = $this->selectTask($attribute->dt_nascimento, $arr_AttributeTask['dt_nascimento']);
//                $pessoa->setDtNascimento($dt_nascimento);
//            }
//
//            if (isset($attribute->idade)) {
//                $idade = $this->selectTask($attribute->idade, $arr_AttributeTask['idade']);
//                $pessoa->setIdade($idade);
//            }
//
//            if (isset($attribute->estado)) {
//                $estado = $this->selectTask($attribute->estado, $arr_AttributeTask['estado']);
//                $pessoa->setEstado($estado);
//            }
//
//            if (isset($attribute->altura)) {
//                $altura = $this->selectTask($attribute->altura, $arr_AttributeTask['altura']);
//                $pessoa->setAltura($altura);
//            }
//
//            if (isset($attribute->peso)) {
//                $peso = $this->selectTask($attribute->peso, $arr_AttributeTask['peso']);
//                $pessoa->setPeso($peso);
//            }
//
//            if (isset($attribute->pele)) {
//                $pele = $this->selectTask($attribute->pele, $arr_AttributeTask['pele']);
//                $pessoa->setPeso($pele);
//            }
//
//            if (isset($attribute->cor_cabelo)) {
//                $cor_cabelo = $this->selectTask($attribute->cor_cabelo, $arr_AttributeTask['cor_cabelo']);
//                $pessoa->setCorCabelo($cor_cabelo);
//            }
//
//            if (isset($attribute->cor_olho)) {
//                $cor_olho = $this->selectTask($attribute->cor_olho, $arr_AttributeTask['cor_olho']);
//                $pessoa->setCorCabelo($cor_olho);
//            }
//
//            if (isset($attribute->mais_caracteristicas)) {
//                $mais_caracteristicas = $this->selectTask($attribute->mais_caracteristicas, $arr_AttributeTask['mais_caracteristicas']);
//                $pessoa->setCorCabelo($mais_caracteristicas);
//            }
//
//            if (isset($attribute->dt_desaparecimento)) {
//                $dt_desaparecimento = $this->selectTask($attribute->dt_desaparecimento, $arr_AttributeTask['dt_desaparecimento']);
//                $pessoa->setCorCabelo($dt_desaparecimento);
//            }
//
//            if (isset($attribute->local_desaparecimento)) {
//                $local_desaparecimento = $this->selectTask($attribute->local_desaparecimento, $arr_AttributeTask['local_desaparecimento']);
//                $pessoa->setCorCabelo($local_desaparecimento);
//            }
//
//            if (isset($attribute->circunstancia_desaparecimento)) {
//                $circunstancia_desaparecimento = $this->selectTask($attribute->gcircunstancia_desaparecimentoender, $arr_AttributeTask['circunstancia_desaparecimento']);
//                $pessoa->setCorCabelo($circunstancia_desaparecimento);
//            }
//
//            if (isset($attribute->dados_adicionais)) {
//                $dados_adicionais = $this->selectTask($attribute->dados_adicionais, $arr_AttributeTask['dados_adicionais']);
//                $pessoa->setCorCabelo($dados_adicionais);
//            }
//
//            if (isset($attribute->situacao)) {
//                $situacao = $this->selectTask($attribute->situacao, $arr_AttributeTask['situacao']);
//                $pessoa->setCorCabelo($situacao);
//            }
//
//            if (isset($attribute->fonte)) {
//                $fonte = $this->selectTask($attribute->fonte, $arr_AttributeTask['fonte']);
//                $pessoa->setCorCabelo($fonte);
//            }
        }
        return $pessoa;
    }

    public function getArrayAttributeTask($jsonConfig)
    {
        $arr_AttributeTask = array();
        foreach ($jsonConfig->attributes as $attribute) {
            $arr_AttributeTask[$attribute->name] = $attribute->task;
        }
        return $arr_AttributeTask;
    }

}
