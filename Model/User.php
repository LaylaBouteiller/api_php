<?php
class User
{
    //connexion
    private $connexion;
    private $table = "user";

    //object properties
    public $id;
    public $lastname;
    public $firstname;
    public $email;
    public $category_id;
    public $categoriy_name;
    public $created_at;

    /**
     * constructer avec $db pour la connexion à la bdd
     * @param $db
     */
    public function __construct($db)
    {
        $this->connexion = $db;
    }

    /**
     * Lecture des produits
     * 
     */
    public function read()
    {
        // on écrit la request 
        $sql = "SELECT c.name as category_name, u.id, u.firstname, u.lastname, u.email, u.category_id, u.created_at FROM " . $this->table . " u LEFT JOIN category c ON u.category_id = c.id ORDER BY u.created_at DESC";

        //on prépare la request
        $query = $this->connexion->prepare($sql);

        //On execture la request
        $query->execute();

        // on return le resultat
        return $query;
    }

    /**
     * Creer un produit
     * @return void
     */
    public function create()
    {

        //Request SQL
        $sql = "INSERT INTO " . $this->table . " SET firstname=:firstname, lastname=:lastname, email=:email, category_id=:category_id";
        //Prepare la request
        $query = $this->connexion->prepare($sql);

        //Protection contre injection
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        //Ajout des données protegées
        $query->bindParam(":firstname", $this->firstname);
        $query->bindParam(":lastname", $this->lastname);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":category_id", $this->category_id);

        //On execture la request
        if ($query->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Lire un produit
     * @return void
     */
    public function readOne()
    {
        //On ecrit la request
        $sql = "SELECT c.name as category_name, u.id, u.lastname, u.firstname, u.email, u.category_id, u.created_at FROM " . $this->table . " u LEFT JOIN category c ON u.category_id = c.id WHERE u.id = ? LIMIT 0,1";

        //On prépare la request
        $query = $this->connexion->prepare($sql);

        // On attache l'id
        $query->bindParam(1, $this->id);

        //On execture la request
        $row = $query->fetch(PDO::FETCH_ASSOC);

        //On hydrate l'objet
        $this->lastname = $row['lastname'];
        $this->firstname = $row['firstname'];
        $this->email = $row['email'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    /**
     * Supprimer un produit
     * @return void
     */
    public function delete()
    {
        //on ecrit la request
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";

        //on prepare la request
        $query = $this->connexion->prepare($sql);

        //On securise les données
        $this->id = htmlspecialchars(strip_tags($this->id));

        //On attache l'id
        $query->bindParam(1, $this->id);

        //On execture la request
        if ($query->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Mettre à jour un produit
     * @return void
     */
    public function update()
    {
        //On ecrit la request
        $sql = "UPDATE " . $this->table . " SET firstname=:firstname, lastname=:lastname, email=:email, category_id=:category_id WHERE id = :id ";

        // On prépare la request
        $query = $this->connexion->prepare($sql);

        //On securise les données;
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // On attache les variables
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':email', $this->email);
        $query->bindParam(':category_id', $this->category_id);
        $query->bindParam(':id', $this->id);

        // On exécute
        if ($query->execute()) {
            return true;
        }

        return false;
    }
}
