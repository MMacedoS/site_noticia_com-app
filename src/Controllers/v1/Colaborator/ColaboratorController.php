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

        return $this->router->view('colaborator/index', [
            'colaboradores' => $paginatedBoards,
            'links' => $paginator->links(),
            'name_email' => $params['name_email'] ?? null,
            'situation' => $params['situation'] ?? null
        ]);
    }

    public function create(Request $request){
        return $this->router->view('colaborator/create', [
            'active' => 'cadastro',
        ]);
    }

    public function store(Request $request){
        $data = $request->getBodyParams();

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:100',
            'email' => 'required|email',
            'doc' => 'required'
        ];

        if(!$validator->validate($rules)){
            return $this->router->view('colaborator/create', [
                'errors' => $validator->getErrors()
            ]);
        }

        try{
            $create = $this->colaboradorRepository->saveAll($data);

            if(is_null($create)){
                return $this->router->view('colaborator/create', []);
            }

            return $this->router->redirect('colaboradores');
        }catch(\Exception $e){
            return $this->router->view('colaborator/create', [
                'active' => 'cadastro',
                'error' => 'Erro ao criar o setor: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Request $request, $id){
        $colaborador = $this->colaboradorRepository->findByUuid($id);

        if(!$colaborador){
            return $this->router->redirect('colaboradores');
        }

        return $this->router->view('colaborator/edit', [
            'active' => 'cadastro',
            'colaborador' => $colaborador
        ]);
    }

}