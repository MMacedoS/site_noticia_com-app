<?php

namespace App\Repositories\New;

use App\Config\Database;
use App\Interfaces\New\INoticiaRepository;
use App\Models\New\Noticia;
use App\Repositories\Traits\FindTrait;
use App\Utils\LoggerHelper;

class NoticiaRepository implements INoticiaRepository{

    const CLASS_NAME = Noticia::class;
    const TABLE = 'noticias';

    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new Noticia();
    }

    public function allNotices(array $params = []){
        $sql = "SELECT * FROM " . self::TABLE;

        $conditions = [];
        $bindings = [];

        if (isset($params['title'])) {
            $conditions[] = "titulo = :titulo";
            $bindings[':titulo'] = "%" . $params['title'] . "%";
        }

        if (isset($params['author'])) {
            $conditions[] = "autor = :autor";
            $bindings[':autor'] = "%" . $params['author'] . "%";
        }

        if (isset($params['situation']) && $params != '') {
            $conditions[] = "ativo = :ativo";
            $bindings[':ativo'] = $params['situation'];
        }

        if(count($conditions) > 0){
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);
    }

    

}