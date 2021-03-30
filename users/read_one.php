<?php

//headers requis   
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//On verifie la methode
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    //On inclut les fichiers de configuration et d'acces aux données
    include_once '../config/Database.php';
    include_once '../Model/User.php';

    //On instencie la base de donnée
    $database = new Database();
    $db = $database->getConnexion();

    //On instanvie les users
    $user = new User($db);

    $donnees = json_decode(file_get_contents("php://input"));


    if (!empty($donnees->id)) {
        $user->id = $donnees->id;

        //On recupere le user
        $user->readOne();

        //on verifie si le user existe
        if ($user->lastname != null) {

            $prod = [
                'id' => $user->id,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'email' => $user->email,
                'category_id' => $user->category_id,
                'category_name' => $user->category_name
            ];
            //On renvoi status reponse 200 OK
            http_response_code(200);
            // On encode et on envoie
            echo json_encode($prod);
        } else {
            //404
            http_response_code(404);
            echo json_encode(['message' => 'Le user n\'existe pas']);
        }
    }
} else {
    //On gere l'erreur
    http_response_code(405);
    echo json_encode(['message' => 'La methode n\'est pas autorisée']);
}
