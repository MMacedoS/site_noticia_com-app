<?php

namespace App\Repositories\Colaborator;

use App\Config\Database;
use App\Interfaces\Colaborator\IColaboradorRepository;
use App\Models\Colaborator\Colaborador;
use App\Repositories\Traits\FindTrait;
use App\Utils\LoggerHelper;


class ColaboradorRepository implements IColaboratorRepository {

    const CLASS_NAME = Colaborador::class;
    const TABLE = 'colaborador';

    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new Colaborador();
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

    public function saveAll(array $data){
        if(empty($data)){
            return null;
        }

        try{
            $userData = array_merge($data, [
                'password' => 'sindsmut123'
            ]);

            $user = $this->usuarioRepository->create($userData);

            $personData = array_merge($data, ['usuario_id' => $user->id]);
            $person = $this->pessoaFisicaRepository->create($personData);

            $colaboratorData = array_merge($data, ['pessoa_fisica_id' => $person->id]);
            $colaborator= $this->create($colaboratorData);

            return $colaborator;

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function create(array $params){
        $findColaborator = $this->findByColaboratorId($params);
        if($findColaborator){
            return $findColaborator;
        }

        $colaborator = $this->model->create($params);

        try{
            $sql = "INSERT INTO " . self::TABLE . "
                set
                    uuid = :uuid,
                    setor_id = :sector_id,
                    pessoa_fisica_id = :person_id
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':uuid' => $colaborator->uuid,
                ':sector_id' => $colaborator->setor_id,
                ':person_id' => $colaborator->pessoa_fisica_id
            ]);

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function updateAll(array $data){
        if(empty($data)){
            return null;
        }

        try{
            $user = $this->usuarioRepository->update($data, $data['usuario_id']);
            if(is_null($user)){
                return null;
            }

            $person = $this->pessoaFisicaRepository->update($data, $data['pessoa_fisica_id']);
            if(is_null($person)){
                return null;
            }

            $colaborator = $this->update($data, (int)$data['id']);
            if(is_null($colaborador)){
                return null;
            }

            return $colaborator;

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function update(array $data, int $id){
        $colaborator = $this->findById((int)$id);

        if(is_null($colaborator)){
            return null;
        }

        $colaborator = $this->model->update(
            $data,
            $colaborator
        );

        try{
            $stmt = $this->conn->prepare(
                "UPDATE " . self::TABLE . " 
                    set
                        pessoa_fisica_id = :person_id,
                        setor_id = :sector_id,
                        ativo = :active
                    WHERE id = :id
                "
            );

            $updated = $stmt->execute([
                ':person_id' => $colaborator->pessoa_fisica_id,
                ':sector_id' => $colaborator->setor_id,
                ':active' => $colaborator->ativo
            ]);

            if(is_null($updated)){
                return null;
            }

            $updated = $this->findById((int)$id);

            return $updated;

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }

    public function deleteAll($colaborator){
        $person = $this->pessoaFisicaRepository->findById($colaborator->pessoa_fisica_id);

        $this->usuarioRepository->delete($person->usuario->id);

        $this->pessoaFisicaRepository->delete($pessoa_fisica->id);

        return $this->delete($colaborator->id);
    }

    public function delete(int $id){
        $stmt = $this->conn->prepare(
            "UPDATE " . self::TABLE . " 
                SET
                    ativo = 0
                WHERE id = :id
            "
        );

        $updated = $stmt->execute([':id' => $id]);

        return $updated;
    }

    public function findByColaboratorId(string $data) : ?Colaborador{
        try{

            $conditions = [];
            $params = [];

            if(!empty($data['person_id'])){
                $conditions[] = "pessoa_fisica_id = :pessoa_fisica_id";
                $params[':pessoa_fisica_id'] = $data['person_id'];
            }

            if(empty($conditions)){
                return null;
            }

            $sql = "SELECT FROM " . self::TABLE . " WHERE " . implode(" AND ", $conditions);
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::CLASS_NAME);
            $result = $stmt->fetch();  

            return $result ?: null;

        }catch(\Throwable $th){
            LoggerHelper::logInfo($th->getMessage());
            return null;
        }finally{
            Database::getInstance()->closeConnection();
        }
    }
}