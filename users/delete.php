<?php

//headers requis   
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//On verifie la methode
if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    //On inclut les fichiers de configuration et d'acces aux données
    include_once '../config/Database.php';
    include_once '../Model/User.php';

    //On instencie la base de donnée
    $database = new Database();
    $db = $database->getConnexion();

    //On instanvie les User
    $user = new User($db);

    $donnees = json_decode(file_get_contents("php://input"));

    if (!empty($donnees->id)) {
        $user->id = $donnees->id;

        if ($user->delete()) {
            //Ici suppression ok
            //code 200
            http_response_code(200);
            echo json_encode(['message' => 'La suppression a ete effectuée']);
        } else {
            //Ici suppression pas ok 
            //Code 503
            http_response_code(503);
            echo json_encode(['message' => 'La suppression n\'a pas ete effectuée']);
        }
    }
} else {
    //On gere l'erreur
    http_response_code(405);
    echo json_encode(['message' => 'La suppression n\'est pas autorisée']);
}
