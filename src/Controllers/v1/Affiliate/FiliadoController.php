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

    public function index(Request $request){
        $params = $request->getQueryParams();

        $filiados = $this->filiadoRepository->allAffiliates($params);
        $perpage = 10;
        $currentPage = $request->getParams('page') ? (int)$request->getParams('page') : 1;
        $paginator = new Paginator($filiados, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        return $this->router->view('affiliate/index', [
            'filiados' => $paginatedBoards,
            'links' => $paginator->links(),
            'name_email' => $params['name_email'] ?? null,
            'situation' => $params['situation'] ?? null
        ]);
    }

    public function create(Request $request){
        return $this->router->view('affiliate/create', [
            'active' => 'cadastro'
        ]);
    }

    public function store(Request $request){
        $data = $request->getBodyParams();

        $validator = new validator($data);

        $rules = [
            'name' => 'required|min:1|max:100',
            'email' => 'required|email',
            'doc' => 'required'
        ];

        if(!$validator->validate($rules)){
            return $this->router->view('affiliate/create', [
                'errors' => $validator->getErrors()
            ]);
        }

        try{
            $create = $this->filiadoRepository->saveAll($data);

            return $this->router->redirect('filiados');
        }catch(\Exception $e){
            return $this->router->view('affiliate/create', [ 
                'active' => 'cadastro',
                'error' => 'Erro ao criar o colaborador: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id){}

    public function update(Request $request, $id){}

    public function destroy(Request $request, $id){}
}