<?php 

namespace App\Controllers\v1\New;

use App\Controllers\Controller;
use App\Interfaces\New\INoticiaRepository;
use App\Request\Request;

class NoticiaController extends Controller{

    protected $noticiaRepository;

    public function __construct(INoticiaRepository $noticiaRepository){
        parent::__construct();
        $this->noticiaRepository = $noticiaRepository;
    }

    public function index(Request $request){
        $params = $request->getQueryParams();

        $noticias = $this->noticiaRepository->allNotices($params);
        $perPage = 10;
        $currentPage = $request->getParam('page') ? (int)$request->getParams('page') : 1;
        //$paginator = new Paginator($noticias, $perPage, $currentPage);
        //$paginatedBoards = $paginator->getPaginatedItems();

        return $this->router->view('new/index', [
            'active' => 'cadastro',
            'noticias' => $noticias,
            //'links' => $paginator->links(),
            'title' => $params['title'] ?? null,
            'author' => $params['author'] ?? null,
            'situation' => $params['situation'] ?? null
        ]);
    }

    public function create(Request $request){
        return $this->router->view('new/create', [
            'active' => 'cadastro'
        ]);
    }

    public function store(Request $request){
        $data = $request->getBodyParams();

        try{
            $create = $this->noticiaRepository->create($data);
            
            if(is_null($create)){
                return $this->router->view('new/create', [
                    'active' => 'cadastro',
                    'error' => ''
                ]);
            }

            return $this->router->redirect('noticia');

        }catch(\Exception $e){
            return $this->router->view('new/create', [
                'active' => 'cadastro',
                'error' => 'Erro ao criar a notícia: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id){
        $noticia = $this->noticiaRepository->findByUuid($id);

        if(!$noticia){
            return $this->router->redirect('noticia');
        }

        return $this->router->view('new/edit', [
            'active' => 'cadastro',
            'noticia' => $noticia
        ]);
    }

    public function update(Request $request, $id){
        $noticia = $this->noticiaRepository->findByUuid($id);

        if(!$noticia){
            return $this->router->view('new/edit', [
                'active' => 'cadastro',
                'noticia' => $noticia
            ]);
        }

        $data = $request->getBodyParams();

        try{
            $this->noticiaRepository->update($data, $noticia->id);
            return $this->router->redirect('noticia');
        }catch(\Exception $e){
            return $this->router->view('new/edit', [
                'active' => 'cadastro',
                'error' => 'Erro ao atualizar a notícia: ' . $e->getMessage(),
                'noticia' => $noticia
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $noticia = $this->noticiaRepository->findByUuid($id);

        if(!$noticia){
            return $this->router->redirect('noticia');
        }

        try{
            $this->noticiaRepository->delete($noticia->id);
            return $this->router->redirect('noticia');

        }catch(\Exception $e){
            return $this->router->view('new/index', [
                'active' => 'cadastro',
                'error' => 'Erro ao excluir a notícia: ' . $e->getMessage()
            ]);
        }
    }

}