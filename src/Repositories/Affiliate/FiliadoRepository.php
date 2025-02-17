<?php

namespace App\Repositories\Affiliate;

use App\Config\Database;
use App\Interfaces\Affiliate\IAffiliateRepository;
use App\Models\Affiliate\Filiado;
use App\Repositories\Traits\FindTrait;
use App\Utils\LoggerHelper;

class FiliadoRepository implements IAffiliateRepository{

    const CLASS_NAME = Filiado::class;
    const TABLE = 'filiado';

    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new Filiado;
    }

    public function allAffiliate(array $params = []){
        $sql = "SELECT
                f.*,(
                    SELECT
                        JSON_OBJECT(
                            'id', pf.id,
                            'nome', pf.nome
                            'email', pf.email
                        )
                    FROM pessoa_fisica pf
                    WHERE pf.id = f.pessoa_fisica_id
                ) AS pessoa_fisica
                FROM " . self::TABLE . " 
                f LEFT JOIN pessoa_fisica pf ON f.pessoa_fisica_id = pf.id
            ";
        
        $conditions = [];
        $bindings = [];

        if(isset($params['name_email'])){
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