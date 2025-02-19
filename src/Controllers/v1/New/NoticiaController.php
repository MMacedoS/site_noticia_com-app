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
            'noticias' => $paginatedBoards,
            'links' => $paginator->links(),
            'title' => $params['title'],
            'author' => $params['author'],
            'situation' => $params['situation']
        ]);
    }

}