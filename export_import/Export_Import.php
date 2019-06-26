<?php

namespace export_import;


interface Export_Import
{
    public function export($dataV0);

    public function import($dataV0);
}