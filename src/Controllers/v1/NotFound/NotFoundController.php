<?php

namespace App\Controllers\v1\NotFound;

use App\Controllers\Controller;
use App\Request\Request;

class NotFoundController extends Controller {
  
    public $router;

    public function __construct() 
    {
        parent::__construct();  
    }

    public function index(Request $request) 
    {
        return $this->router->view('404/index', ['active' => 'pedagogico',]); 
    }
}