<?php

namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;

class ErrorHandlingCountroller{
    public function index(){
        App::render('error.index');
    }
}