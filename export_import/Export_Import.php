<?php

namespace export_import;


interface Export_Import
{
    public function exportPessoa($pessoa);

    public function importPessoa($pessoa);
}