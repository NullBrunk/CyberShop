<?php

namespace App\Http;


class Sql {

    /**
     * Facilitate the building of an SQL query
     *
     * @param string  $query    The SQL query to execute
     * @param array   $params   The parameters that will be passed to the request
     *                          Default: empty array
     * 
     * @param string  $mail     
     * 
     * @return array    An array with the result of the request
     * 
     */


    public static function query(string $query, array $params = [], string $method = "all")
    {
        $pdo = new \PDO("mysql:host=localhost;dbname=" . env("DB_DATABASE"), env("DB_USERNAME"), env("DB_PASSWORD"));

        $sql = $pdo -> prepare($query);
        $sql -> execute($params);
        
        if ($method === "all"){
            return $sql -> fetchall(\PDO::FETCH_ASSOC);
        } 
        else if($method === "fetch"){
            return $sql -> fetch(\PDO::FETCH_ASSOC);
        }
    }
}

