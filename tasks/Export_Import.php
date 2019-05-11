<?php

namespace tasks;


interface Export_Import
{
    public function exportPessoa($fileTxt, $pessoa);

    public function importPessoa($fileTxt, $pessoa);
}