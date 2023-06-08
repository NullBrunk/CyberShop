<?php

$pdo = new PDO(
    "mysql:host=localhost;dbname=" . env("DB_DATABASE"), 
    env("DB_USERNAME"), 
    env("DB_PASSWORD")
);