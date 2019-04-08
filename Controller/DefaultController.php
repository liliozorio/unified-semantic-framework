<?php
require __DIR__ . '/../model/Pessoa.php';
require __DIR__ . '/../model/BD.php';
require __DIR__ . '/../model/SelectTask.php';
require __DIR__ . '/../tasks/LowerCaseNormalizerTask.php';
require __DIR__ . '/../tasks/IBGELinearRegression.php';
require __DIR__ . '/../tasks/CityCanonicaName.php';
require __DIR__ . '/../tasks/DBPediaSpotlightAnnotation.php';
require __DIR__ . '/../tasks/CompoundTask.php';


use model\Pessoa;
use model\BD;
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
        $pessoa->printPessoa();

    }

    public function criaPessoa($selectTask)
    {
        /** aquivo json com dos atributos coletados*/
        $arquivoScraping = file_get_contents(__DIR__ . '/../json/teste.json');
        $jsonScraping = json_decode($arquivoScraping);

        $pessoa = new Pessoa();
        foreach ($jsonScraping->attributes as $name => $attribute) {
            if (isset($attribute->name)) {
                $name = $selectTask->getAttributeProcessed($attribute->name, 'name');
                $pessoa->setNome($name);
            }

            if (isset($attribute->sexo)) {
                $sexo = $selectTask->getAttributeProcessed($attribute->sexo, 'sexo');
                $pessoa->setSexo($sexo);
            }

            if (isset($attribute->cidade)) {
                $cidade = $selectTask->getAttributeProcessed($attribute->cidade,'cidade');
                $pessoa->setCidade($cidade);
            }

            if (isset($attribute->apelido)) {
                $apelido = $selectTask->getAttributeProcessed($attribute->apelido, 'apelido');
                $pessoa->setApelido($apelido);
            }

            if (isset($attribute->dt_nascimento)) {
                $dt_nascimento = $selectTask->getAttributeProcessed($attribute->dt_nascimento, 'dt_nascimento');
                $pessoa->setDtNascimento($dt_nascimento);
            }

            if (isset($attribute->idade)) {
                $idade = $selectTask->getAttributeProcessed($attribute->idade, 'idade');
                $pessoa->setIdade($idade);
            }

            if (isset($attribute->estado)) {
                $estado = $selectTask->getAttributeProcessed($attribute->estado, 'estado');
                $pessoa->setEstado($estado);
            }

            if (isset($attribute->altura)) {
                $altura = $selectTask->getAttributeProcessed($attribute->altura, 'altura');
                $pessoa->setAltura($altura);
            }

            if (isset($attribute->peso)) {
                $peso = $selectTask->getAttributeProcessed($attribute->peso, 'peso');
                $pessoa->setPeso($peso);
            }

            if (isset($attribute->pele)) {
                $pele = $selectTask->getAttributeProcessed($attribute->pele, 'pele');
                $pessoa->setPele($pele);
            }

            if (isset($attribute->cor_cabelo)) {
                $cor_cabelo = $selectTask->getAttributeProcessed($attribute->cor_cabelo, 'cor_cabelo');
                $pessoa->setCorCabelo($cor_cabelo);
            }

            if (isset($attribute->cor_olho)) {
                $cor_olho = $selectTask->getAttributeProcessed($attribute->cor_olho, 'cor_olho');
                $pessoa->setCorOlho($cor_olho);
            }

            if (isset($attribute->mais_caracteristicas)) {
                $mais_caracteristicas = $selectTask->getAttributeProcessed($attribute->mais_caracteristicas, 'mais_caracteristicas');
                $pessoa->setMaisCaracteristicas($mais_caracteristicas);
            }

            if (isset($attribute->dt_desaparecimento)) {
                $dt_desaparecimento = $selectTask->getAttributeProcessed($attribute->dt_desaparecimento, 'dt_desaparecimento');
                $pessoa->setDtDesaparecimento($dt_desaparecimento);
            }

            if (isset($attribute->local_desaparecimento)) {
                $local_desaparecimento = $selectTask->getAttributeProcessed($attribute->local_desaparecimento, 'local_desaparecimento');
                $pessoa->setLocalDesaparecimento($local_desaparecimento);
            }

            if (isset($attribute->circunstancia_desaparecimento)) {
                $circunstancia_desaparecimento = $selectTask->getAttributeProcessed($attribute->gcircunstancia_desaparecimentoender, 'circunstancia_desaparecimento');
                $pessoa->setCircunstanciaDesaparecimento($circunstancia_desaparecimento);
            }

            if (isset($attribute->dados_adicionais)) {
                $dados_adicionais = $selectTask->getAttributeProcessed($attribute->dados_adicionais, 'dados_adicionais');
                $pessoa->setDadosAdicionais($dados_adicionais);
            }

            if (isset($attribute->situacao)) {
                $situacao = $selectTask->getAttributeProcessed($attribute->situacao, 'situacao');
                $pessoa->setSituacao($situacao);
            }

            if (isset($attribute->fonte)) {
                $fonte = $selectTask->getAttributeProcessed($attribute->fonte, 'fonte');
                $pessoa->setFonte($fonte);
            }

            if (isset($attribute->imagem)) {
                $imagem = $selectTask->getAttributeProcessed($attribute->imagem, 'imagem');
                $pessoa->setImagem($imagem);
            }
        }
        return $pessoa;
    }

}
