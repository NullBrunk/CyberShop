<?php

namespace App\Http;


# With this you can do directly :
#   $data = Sql::query("SELECT * FROM table WHERE a=:i", ["i" => "Hello"])


class Sql {

    public static function query(string $query, array $params = []) : array
    {
        $pdo = new \PDO("mysql:host=localhost;dbname=" . env("DB_DATABASE"), env("DB_USERNAME"), env("DB_PASSWORD"));

        $sql = $pdo -> prepare($query);
        $sql -> execute($params);
        return $sql -> fetchall(\PDO::FETCH_ASSOC);
    }
}

