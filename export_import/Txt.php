<?php
namespace export_import;

require_once 'Export_Import.php';

class Txt implements Export_Import
{
    public function exportPessoa($pessoa)
    {
        $fileTxt = "exportPessoa.txt";
        $objData = serialize($pessoa);
        $file = fopen($fileTxt, "w");
        fwrite($file, $objData);
        fclose($file);
    }

    public function importPessoa($pessoa)
    {
        $fileTxt = "importPessoa.txt";
        $objData = file_get_contents($fileTxt);
        $obj = unserialize($objData);

        if (!empty($obj)) {
            foreach ($pessoa->arr_attributes as $attribute){
                $pessoa->setAttribute($attribute, $obj->$attribute);
            }
        }
    }
}