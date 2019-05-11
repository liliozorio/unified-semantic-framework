<?php

namespace model;


interface Export_Import
{
    public function exportPessoa($pessoa);

    public function importPessoa($pessoa);
}