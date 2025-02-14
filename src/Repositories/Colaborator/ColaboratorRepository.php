<?php

namespace App\Repositories\Colaborator;

use App\Config\Database;
use App\Interfaces\Colaborator\IColaboratorRepository;
use App\Models\Colaborator\Colaborador;
use App\Repositories\Traits\FindTrait;
use App\Utils\LoggerHelper;


class ColaboratorRepository implements IColaboratorRepository {

    const CLASS_NAME = Colaborador::class;
    const TABLE = 'colaborador';

    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new Colaborator();
    }

    public function allColaborators(array $params = []){
        $sql = "SELECT
                c.*,(
                    SELECT 
                        JSON_OBJECT(
                            'id', pf.id,
                            'nome', pf.nome,
                            'email', pf.email
                        )
                    FROM pessoa_fisica pf
                    WHERE pf.id = c.pessoa_fisica_id
                ) AS pessoa_fisica
                FROM " . self::TABLE . " 
                c LEFT JOIN pessoa_fisica pf ON c.pessoa_fisica_id = pf.id
            ";

        $conditions = [];
        $bindings = [];

        if(isset($params['name_email'])) {
            $conditions[] = "(pf.nome LIKE :name_email OR pf.email LIKE :name_email)";
            $bindings[':name_email'] = '%' . $params['name_email'] . '%';
        }

        if(isset($params['situation']) && $params['situation'] != '') {
            $conditions[] = "ativo = :situation";
            $bindings[':situation'] = $params['situation'];
        }

        if(count($conditions) > 0){
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);
    }

}