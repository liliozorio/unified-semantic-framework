<?php

namespace model;

use ReflectionClass;
use scraping\PoliciaCivilGO;
use scraping\PoliciaMilitarSC;

class ScrapingModel
{
    private $scrapingClass;

    function __construct($scrapingClass)
    {

        try {
            $this->scrapingClass = new ReflectionClass('scraping\\'.$scrapingClass);
            $this->scrapingClass = $this->scrapingClass->newInstance();
        } catch (\ReflectionException $e) {
            echo("<b>Erro:</b> ". $e->getMessage());
        }
    }

    function scraping(){
        $this->scrapingClass->scraping();
    }
}