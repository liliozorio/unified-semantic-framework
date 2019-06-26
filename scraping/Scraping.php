<?php

namespace scraping;

interface Scraping
{
    public function scraping();

    public function generateJson($name);
}