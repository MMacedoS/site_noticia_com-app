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
                'error' => 'Erro ao criar o filiado: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id){
        $filiado = $this->filiadoRepository->findByUuid($id);

        if(!filiado){
            return $this->router->redirect('filiados');
        }

        return $this->router->view('affiliate/edit', [
            'active' => 'cadastro',
            'filiado' => $filiado
        ]);
    }

    public function update(Request $request, $id){
        $filiado = $this->filiadoRepository->findByUuid($id);

        if(!$filiado){
            return $this->router->redirect('filiado');
        }

        $person = $this->pessoaFisicarepository->findById($filiado->pesso_fisica_id);

        $validator = new Validator($data);

        $rules = [
            'name' => 'required|min:1|max:100',
            'email' => 'required|email',
            'doc' => 'required'
        ];

        if(!validator->validate($rules)){
            return $this->router->view('affiliate/create', [
                'errors' => $validator->getErrors()
            ]);
        }

        $data = $request->getBodyParams();
        $data['usuario_id'] = $pessoa_fisica->usuario_id;
        $data['pessoa_fisica_id'] = $pessoa_fisica->id;
        $data['id'] = $filiado->id;
        $data['setor_id'] = $filiado->setor_id;

        try{
            $update = $this->filiadoRepository->updateAll($data);
            return $this->router->redirect('filiados');
        }catch(\Exception $e){
            return $this->router->view('affiliate/edit', [
                'active' => 'cadastro',
                'error' => 'Erro ao atualizar o filiado: ' . $e->getMessage(),
                'filiado' => $filiado
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $filiado = $this->filiadoRepository->findByUuid($id);

        if(!$filiado){
            return $this->router->view('affiliate/index', [
                'active' => 'cadastro'
            ]);
        }

        try{
            $this->filiadoRepository->deleteAll($filiado);
            return $this->router->redirect('filiado');
        }catch(\Exception $e){
            return $this->router->view('affiliate/index', [
                'active' => 'cadastro',
                'error' => 'Erro ao excluir o filiado: ' . $e->getMessage()
            ]);
        }
    }
}