<?php

namespace App\Http;

class Query {

    private $pdo;

    public function __construct(){
        $this -> pdo = config("app.pdo");
    }

    public function query(string $query, array $params = []) : array
    {
        $sql = $this -> pdo -> prepare($query);
        $sql -> execute($params);
        return $sql -> fetchall(\PDO::FETCH_ASSOC);
    }

}