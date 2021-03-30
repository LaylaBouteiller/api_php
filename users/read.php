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

    //On recupere les données
    $stmt = $user->read();

    //On verifie si on a au moins 1 user
    if ($stmt->rowCount() > 0) {
        //On initialise un tableau associatif
        $tableauUsers = [];
        $tableauUsers['users'] = [];

        //on parcourt les users
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $prod = [
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'category_id' => $category_id,
                'category_name' => $category_name
            ];
            $tableauusers['users'][] = $prod;
        }

        //On renvoi status reponse 200 OK
        http_response_code(200);
        // On encode et on envoie
        echo json_encode($tableauusers);
    }
} else {
    //On gere l'erreur
    http_response_code(405);
    echo json_encode(['message' => 'La methode n\'est pas autorisée']);
}
