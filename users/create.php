<?php

//headers requis   
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//On verifie la methode
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //On inclut les fichiers de configuration et d'acces aux données
    include_once '../config/Database.php';
    include_once '../Model/User.php';

    //On instencie la base de donnée
    $database = new Database();
    $db = $database->getConnexion();

    //On instanvie les users
    $user = new User($db);

    //on recupere les informations envoyées
    $donnees = json_decode(file_get_contents('php://input'));

    if (!empty($donnees->firstname) && !empty($donnees->lastname) && !empty($donnees->email) && !empty($donnees->category_id)) {
        ///Ici on a recu les donnees
        //On hudrate notre objet
        $user->firstname = $donnees->firstname;
        $user->lastname = $donnees->lastname;
        $user->email = $donnees->email;
        $user->category_id = $donnees->category_id;

        if ($user->create()) {
            //Ici la creation à fonctionner
            //On renvoyé un code 201
            http_response_code(201);
            echo json_encode(["message" => "l'ajout a été effectué"]);
        } else {
            //Ici la creation n'a pas fonctionné
            //On renvoie un code 503
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas été effectué"]);
        }
    }
} else {
    //On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La methode n'est pas autorisée"]);
}
