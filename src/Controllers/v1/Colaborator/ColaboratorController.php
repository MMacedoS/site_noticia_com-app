<?php

namespace App\Controllers\v1\Colaborator;

use App\Controllers\Controller;
use App\Interfaces\Colaborator\IColaboradorRepository;
use App\Request\Request;
use App\Utils\Paginator;
use App\Utils\Validator;


class ColaboradorController extends Controller{

    protected $colaboradorRepository;
    protected $pessoaFisicaRepository;

    public function __construct(
        IColaboratorRepository $colaboradorRepository,
        IPessoaFisicaRepository $pessoaFisicaRepository
    ){
        parent::__construct();
        $this->colaboradorRepository = $colaboradorRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;
    }

    public function index(Request $request){
        $params = $request->getQueryParams();

        $colaboradores = $this->colaboradorRepository->allColaborators($params);
        $perPage = 10;
        $currentPage = $request->getParam('page') ? (int)$request->getParams('page') : 1;
        $paginator = new Paginator($colaboradores, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        return $this->router->view('/colaborator/index', [
            'colaboradores' => $paginatedBoards,
            'links' => $paginator->links(),
            'name_email' => $params['name_email'] ?? null,
            'situation' => $params['situation'] ?? null
        ]);
    }

}