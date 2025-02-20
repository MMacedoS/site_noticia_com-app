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
        $paginator = new Paginator($noticias, $perPage, $currentPage);
        $paginatedBoards = $paginator->getPaginatedItems();

        return $this->router->view('new/index', [
            'active' => 'cadastro',
            'noticias' => $paginatedBoards,
            'links' => $paginator->links(),
            'title' => $params['title'],
            'author' => $params['author'],
            'situation' => $params['situation']
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
            $this->noticiaRepository->create($data);
            return $this->router->view('new/index', [
                'active' => 'cadastro'
            ]);

        }catch(\Throwable $th){
            return $this->router->view('new/create', [
                'active' => 'cadastro',
                'error' => 'Erro ao criar a notícia: ' . $e->getMessage()
            ]);
        }
    }

}