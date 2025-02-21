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
            $conditions[] = "titulo LIKE :titulo";
            $bindings[':titulo'] = "%" . $params['title'] . "%";
        }

        if (isset($params['author'])) {
            $conditions[] = "autor LIKE :autor";
            $bindings[':autor'] = "%" . $params['author'] . "%";
        }

        if (isset($params['situation']) && $params != '') {
            $conditions[] = "ativo = :ativo";
            $bindings[':ativo'] = $params['situation'];
        }

        if(count($conditions) > 0){
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);  
    }

    public function create(array $params){
        $noticia = $this->model->create($params);

        try{
            $stmt = $this->conn->prepare(
                "INSERT INTO " . self::TABLE . "
                    SET
                        uuid = :uuid,
                        titulo = :titulo,
                        resumo = :resumo,
                        noticia = :noticia,
                        autor = :autor,
                        fonte = :fonte,
                        tag = :tag,
                        ativo = :ativo,
                        link = :link
                "
            );

            $create = $stmt->execute([
                ':uuid' => $noticia->uuid,
                ':titulo' => $noticia->titulo,
                ':resumo' => $noticia->resumo,
                ':noticia' => $noticia->noticia,
                ':autor' => $noticia->autor,
                ':fonte' => $noticia->fonte,
                ':tag' => $noticia->tag,
                ':ativo' => $noticia->ativo,
                ':link' => $noticia->link
            ]);

            if(!$create){
                return null;
            }

            return $this->findByUuid($noticia->uuid);

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function update(array $params, int $id){
        $noticia = $this->findById($id);

        if(is_null($noticia)){
            return null;
        }

        $noticia = $this->model->update(
            $params,
            $noticia
        );

        try{

            $sql = "UPDATE " . self::TABLE . "
                set 
                    titulo = :titulo,
                    resumo = :resumo,
                    noticia = :noticia,
                    autor = :autor,
                    fonte = :fonte,
                    tag = :tag,
                    ativo = :ativo,
                    link = :link
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);

            $update = $stmt->execute([
                ':titulo' => $noticia->titulo,
                ':resumo' => $noticia->resumo,
                ':noticia' => $noticia->noticia,
                ':autor' => $noticia->autor,
                ':fonte' => $noticia->fonte,
                ':tag' => $noticia->tag,
                ':ativo' => $noticia->ativo,
                ':link' => $noticia->link,
                ':id' => $noticia->id
            ]);

            if(is_null($update)){
                return null;
            }

            return $this->findById($id);

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function delete(int $id){
        $stmt = $this->conn->prepare(
            "UPDATE " . self::TABLE . "
                set
                    ativo = 0
                WHERE id = :id
            "
        );

        $update = $stmt->execute([
            ':id' => $id
        ]);

        return $update;
    }

}