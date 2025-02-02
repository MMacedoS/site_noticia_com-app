<?php

namespace App\Controllers\v1\Sector;

use App\Controllers\Controller;
use App\Interfaces\Sector\ISetorRepository;
use App\Request\Request;

class SetorController extends Controller 
{
    protected $setorRepository;

    public function __construct(ISetorRepository $setorRepository)
    {
        parent::__construct();  
        $this->setorRepository = $setorRepository;
    }

    public function index(Request $request) 
    {   
        return $this->router->view('sector/index', ['active' => 'cadastro',]); 
    }

    public function create(Request $request)
    {
        return $this->router->view('sector/create', [
            'active' => 'cadastro',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->getBodyParams();

        try {
            $this->setorRepository->create($data);
            return $this->router->redirect('setor');
        } catch (\Exception $e) {
            return $this->router->view('sector/create', [
                'active' => 'cadastro',
                'error' => 'Erro ao criar o setor: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $sector = $this->setorRepository->findByUuid($id);

        if (!$sector) {
            return $this->router->redirect('setor');
        }

        return $this->router->view('sector/edit', [
            'active' => 'cadastro',
            'sector' => $sector,
        ]);
    }

    public function update(Request $request, $id)
    {
        $sector = $this->setorRepository->findByUuid($id);

        if (!$sector) {
            return $this->router->redirect('setor');
        }

        $data = $request->getBodyParams();

        try {
            $this->setorRepository->update($data, $sector->id);
            return $this->router->redirect('setor');
        } catch (\Exception $e) {
            return $this->router->view('sector/edit', [
                'active' => 'cadastro',
                'error' => 'Erro ao atualizar o setor: ' . $e->getMessage(),
                'sector' => $sector,
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->setorRepository->delete($id);
            return $this->router->redirect('setor');
        } catch (\Exception $e) {
            return $this->router->view('sector/index', [
                'active' => 'cadastro',
                'error' => 'Erro ao excluir o setor: ' . $e->getMessage(),
            ]);
        }
    }
}