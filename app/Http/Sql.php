<?php

namespace App\Http;

class Sql {

    public static function query(string $query, array $params = []) : array
    {
        $pdo = new \PDO("mysql:host=localhost;dbname=" . env("DB_DATABASE"), env("DB_USERNAME"), env("DB_PASSWORD"));

        $sql = $pdo -> prepare($query);
        $sql -> execute($params);
        return $sql -> fetchall(\PDO::FETCH_ASSOC);
    }
}

