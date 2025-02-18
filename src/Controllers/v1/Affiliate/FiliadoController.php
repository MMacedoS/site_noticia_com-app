<?php

namespace App\Controllers\v1\Affiliate;

use App\Controllers\Controller;
use App\Interfaces\Affiliate\IFiliadoRepository;
use App\Request\Request;
use App\Utils\Paginator;
use App\Utils\Validator;

class FiliadoController extends Controller{

    protected $filiadoRepository;
    protected $pessoaFisicaRepository;

    public function __construct(
        IFiliadoRepository $filiadoRepository,
        IPessoaFisicaRepository $pessoaFisicaRepository
    ){
        parent::__construct();
        $this->filiadoRepository = $filiadoRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;


    }

}