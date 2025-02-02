<?php

namespace App\Controllers\v1\Site;

use App\Controllers\Controller;
use App\Request\Request;

class SiteController extends Controller {
  
    public $router;

    public function __construct() 
    {
        parent::__construct();  
    }

    public function index(Request $request) 
    {
        return $this->router->view('site/index', ['active' => 'pedagogico',]); 
    }

    public function about(Request $request) 
    {
        $timeline = [
            [
                "time" => "2003-2000",
                "title" => "Foundation and Early Growth",
                "description" => "Our institution was established in 2000, and by 2003, we had already welcomed our first cohort of students, laying the groundwork for future success."
            ],
            [
                "time" => "2007-2004",
                "title" => "Expansion of Academic Programs",
                "description" => "During this period, we introduced new undergraduate and graduate programs, attracting students from diverse backgrounds and regions."
            ],
            [
                "time" => "2011-2008",
                "title" => "Campus Modernization",
                "description" => "We invested in modernizing our campus facilities, including state-of-the-art classrooms, labs, and recreational spaces, to enhance the student experience."
            ],
            [
                "time" => "2015-2012",
                "title" => "Recognition and Accreditation",
                "description" => "Our institution gained national recognition and accreditation for excellence in education, solidifying our reputation as a leader in academic innovation."
            ],
            [
                "time" => "2019-2016",
                "title" => "We celebrate 28 years with our first graduates",
                "description" => "Our 28th anniversary was marked by the campus renovation, where a meeting with our graduates took place this year."
            ],
            [
                "time" => "2023-2020",
                "title" => "Adapting to a New Era",
                "description" => "In response to global challenges, we embraced digital transformation, offering hybrid learning models and expanding our online resources to ensure continuity in education."
            ]
        ];
        return $this->router->view('site/about', ['active' => 'about', 'timeline' => $timeline]); 
    }

    public function noticias(Request $request) 
    {
        return $this->router->view('site/list-news', ['active' => 'pedagogico',]); 
    }

    public function noticia(Request $request)
    {
        return $this->router->view('site/news', ['active' => 'pedagogico',]); 
    }
}