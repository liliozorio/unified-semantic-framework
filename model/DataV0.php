<?php

namespace model;

interface DataV0
{
    public function setAttribute($attribute, $value);

    public function getAttribute($attribute);
}