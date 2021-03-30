<?php

//headers requis   
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//On verifie la methode
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    //On inclut les fichiers de configuration et d'acces aux données
    include_once '../config/Database.php';
    include_once '../Model/User.php';

    //On instencie la base de donnée
    $database = new Database();
    $db = $database->getConnexion();

    //On instanvie les User
    $user = new User($db);

    $donnees = json_decode(file_get_contents("php://input"));

    if (!empty($donnees->id) && !empty($donnees->firstname) && !empty($donnees->lastname) && !empty($donnees->email) && !empty($donnees->category_id)) {
        //Ici on a recu les données
        //On hydrate notre objet
        $user->id = $donnees->id;
        $user->firstname = $donnees->firstname;
        $user->lastname = $donnees->lastname;
        $user->email = $donnees->email;
        $user->category_id = $donnees->category_id;

        if ($user->update()) {
            //Ici la modification a fonctionnée
            //code 200
            http_response_code(200);
            echo json_encode(['message' => 'La modification a ete effectué']);
        } else {
            //Ici la modification n'a pas fonctionner
            //Code 503
            http_response_code(503);
            echo json_encode(['message' => 'La modification n\'a pas fonctionnée']);
        }
    }
} else {
    //On gere l'erreur
    http_response_code(405);
    echo json_encode(['message' => 'La methode n\'est pas autorisée']);
}
