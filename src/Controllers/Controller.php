<?php

namespace App\Controllers;

use App\Config\Router;

class Controller {
  
    public $router;

    public function __construct() 
    {
        $this->router = new Router();
    }
}