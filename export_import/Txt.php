<?php
namespace export_import;

use function MongoDB\BSON\toJSON;

require_once 'Export_Import.php';

class Txt implements Export_Import
{
    public function export($person)
    {
        $fileTxt = "exportPeople.txt";
        $file = fopen($fileTxt, "a");
        fwrite($file, $person->__toJson().PHP_EOL);
        fclose($file);
    }

    public function import($person)
    {
        $fileTxt = "importPeople.txt";
        $objData = file_get_contents($fileTxt);
        $obj = unserialize($objData);

        if (!empty($obj)) {
            foreach ($person->arr_attributes as $attribute){
                $person->setAttribute($attribute, $obj->$attribute);
            }
        }
    }
}