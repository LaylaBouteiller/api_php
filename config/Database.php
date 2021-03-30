<?php

class Database
{
    //connexion à la base de donnée
    private $host = "localhost";
    private $db_name = "testapi";
    private $username = "root";
    private $password = "";
    private $port = 3306;
    public $connexion;

    //getter pour la connexion 
    public function getConnexion()
    {
        $this->connexion = null;

        try {
            $this->connexion = new PDO("mysql:host" . $this->host . ';port=' . $this->port . ';dbname=' . $this->db_name . ';', $this->username, $this->password);
            $this->connexion->exec('set names utf8');
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }

        return $this->connexion;
    }
}
