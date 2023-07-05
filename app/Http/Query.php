<?php

namespace App\Http;

class Query {

    private $pdo;

    public function __construct(){
        $this -> pdo = new \PDO("mysql:host=localhost;dbname=" . env("DB_DATABASE"), env("DB_USERNAME"), env("DB_PASSWORD"));
    }

    public function query(string $query, array $params = []) : array
    {
        $sql = $this -> pdo -> prepare($query);
        $sql -> execute($params);
        return $sql -> fetchall(\PDO::FETCH_ASSOC);
    }

}