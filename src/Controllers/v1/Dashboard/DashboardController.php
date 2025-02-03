<?php

namespace App\Controllers\v1\Dashboard;

use App\Controllers\Controller;
use App\Request\Request;

class DashboardController extends Controller 
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return $this->router->view('admin/dashboard/index', ['active' => 'cadastro',]); 
    }
}