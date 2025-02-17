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
            $conditions[] = "(pf.nome LIKE :name_email OR pf.email LIKE :name_email)";
            $bindings[':name_email'] = '%' . $params['name_email'] . '%';
        }

        if(isset($params['situation']) && $params['situation'] != ''){
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

    public function saveAll(array $data){
        if(empty($data)){
            return null;
        }

        try{
            $userData = array_merge($data, [
                'password' => 'sindsmut123'
            ]);

            $user = $this->usuarioRepository->create($userData);

            $personData = array_merge($data, ['pessoa_fisica_id' => $user->id]);
            $person = $this->pessoaFisicaRepository->create($personData);

            $affiliateData = array_merge($data, ['pessoa_fisica_id' => $person->id]);
            $affiliate = $this->create($affiliateData);

            return $affiliate;
        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function create(array $params){
        $findAffiliate = $this->findByAffiliateId($params);
        if($findAffiliate){
            return $findAffiliate;
        }

        $affiliate = $this->model->create($params);

        try{
            $sql = "INSERT INTO " . self::TABLE . "
                set
                    uuid = :uuid,
                    pessoa_fisica_id = :person_id
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':uuid' => $affiliate->uuid,
                ':person_id' =>$affiliate->pessoa_fisica_id
            ]);

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function updateAll(array $data){}

    public function update(array $data, int $id){}

    public function deleteAll($affiliate){}

    public function delete(int $id){}

    public function findByAffiliateId(string $id) : ?Filiado{}



}