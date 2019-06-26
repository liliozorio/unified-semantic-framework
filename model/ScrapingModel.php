<?php

namespace model;

use ReflectionClass;


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

    function run()
    {
       $this->scraping();
    }

    function scraping(){
        $this->scrapingClass->scraping();
    }
}