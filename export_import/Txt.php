<?php
namespace export_import;

require_once 'Export_Import.php';

class Txt implements Export_Import
{
    public function export($person)
    {
        $fileTxt = "exportPessoa.txt";
        $objData = serialize($person);
        $file = fopen($fileTxt, "w");
        fwrite($file, $objData);
        fclose($file);
    }

    public function import($person)
    {
        $fileTxt = "importPessoa.txt";
        $objData = file_get_contents($fileTxt);
        $obj = unserialize($objData);

        if (!empty($obj)) {
            foreach ($person->arr_attributes as $attribute){
                $person->setAttribute($attribute, $obj->$attribute);
            }
        }
    }
}