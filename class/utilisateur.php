<?php

/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 */

// Classe utilisateur

class utilisateur{

    private $id;


    private $email;
    private $nom;
    private $prenom;
    private $password;
    private $adresse;
    private $avatar;
    private $telephone;
    private $date;
    private $admin;
    private $existe;
    private $emprunts;
    private $propositions;
    private $solde;



    /**
     * utilisateur constructor. On passe la connexion à la BDD en argument pour éviter une nouvelle connexion inutile,
     * et on ne la stocke pas dans la classe afin d'éviter d'être empéché de la serializer
     * @param $id
     * @param $pdo
     */
    public function __construct($id,$pdo)
    {
        $this->getUserWithPdo($id,$pdo);
    }


    /**
     * @param $id
     * @param $pdo
     * @return string initialise l'utilisateur avec les données de la BDD
     */
    public function getUserWithPdo($id,$pdo)
    {
        $sql = "SELECT *, DATE_FORMAT(date_inscription,'%d/%m/%Y') AS date FROM utilisateur WHERE id = ? OR login = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $id]);

        if ($stmt->rowCount() == 1)
        {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->email =  $row['login'];
            $this->nom =  $row['nom'];
            $this->prenom =  $row['prenom'];
            $this->password =  $row['password'];
            $this->adresse =  $row['adresse'];
            $this->avatar =  $row['avatar'];
            $this->telephone =  $row['telephone'];
            $this->solde =  $row['solde'];
            $this->date =  $row['date'];
            $this->existe = true;

            $sql = "SELECT count(*) as compte FROM emprunt WHERE id_emprunteur = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$row['id']]);
            $res = $stmt->fetch();

            $this->emprunts =  (int)$res['compte'];

            $stmt = $pdo->prepare("SELECT count(*) as compte FROM biens WHERE id_utilisateur = ?");
            $stmt->execute([$row['id']]);
            $res = $stmt->fetch();
            $this->propositions =  (int)($res['compte']);
            $stmt = $pdo->prepare("SELECT count(*) as compte FROM services WHERE id_utilisateur = ?");
            $stmt->execute([$row['id']]);
            $res = $stmt->fetch();
            $this->propositions =  (int)$this->propositions + (int)($res['compte']);

            $sql = "SELECT id_utilisateur FROM droits WHERE id_utilisateur = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0)
                $this->admin = true;
            else
                $this->admin = false;

            return "succes"; // trouvé
        }
        else {
            $this->existe = false;
            return "erreur";
        }// non trouvé, ou plusieurs (normalement impossible)
    }


    /**
     * @param $email
     * @param $password
     * @return string Inscrit l'utilisateur
     */
    public function setByEmail($email,$password){ // Inscrit le membre via son email et pass

        try{
            $pdo = new PDO('mysql:host=mysql-5.nextwab.com;dbname=PL_7783_db;charset=UTF8', "PL7783_admin", "HlinPass");
            $stmt = $pdo->prepare("INSERT INTO utilisateur (login, password) values (?, ?)");
            $stmt->execute([$email, $password]);

            $this->password = $password;
            $this->email =  $email;

            return $this->getUserWithPdo($email,$pdo);

        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage();
            die();
        }
    }


    /**
     * met à jour les données de l'utilisateur
     */
    public function updateUser(){
        $pdo = new PDO('mysql:host=mysql-5.nextwab.com;dbname=PL_7783_db;charset=UTF8', "PL7783_admin", "HlinPass");
        $stmt = $pdo->prepare("UPDATE utilisateur SET login = ?, nom = ?, prenom = ?, adresse = ?, telephone = ?, avatar = ?
WHERE id=?");
        $stmt->execute([$this->email, $this->nom,$this->prenom,$this->adresse,$this->telephone,$this->avatar,$this->id]);
        $stmt->fetch();
        $_SESSION['email'] = $this->email;
    }

    public function updatePassword(){
        {
            $pdo = new PDO('mysql:host=mysql-5.nextwab.com;dbname=PL_7783_db;charset=UTF8', "PL7783_admin", "HlinPass");

            $stmt = $pdo->prepare("UPDATE utilisateur SET password = ? where id = ?");
            $stmt->execute([$this->password, $this->id]);}
    }


    /**
     *
     * Vérifie si l'email n'est pas déjà utilisée par quelqu'un d'autre
     * @param string $email L'email à vérifier
     * @return string ok ou erreur
     */
    public function checkEmail($email){
        $pdo = new PDO('mysql:host=mysql-5.nextwab.com;dbname=PL_7783_db;charset=UTF8', "PL7783_admin", "HlinPass");
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = ? AND id <> ?");
        $stmt->execute([$email, $this->id]);
        $row = $stmt->fetch();

        if ($row[0] == 0)
        {
            return "ok";
        }
        else
        {
            return "erreur";
        }
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }


    public function getPropositions()
    {
        return $this->propositions;
    }

    /**
     * @param mixed $avatar
     */
    public function setPropositions($p)
    {
        $this->propositions = $p;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getExiste()
    {
        return $this->existe;
    }

    /**
     * @param mixed $existe
     */
    public function setExiste($existe)
    {
        $this->existe = $existe;
    }

    /**
     * @param mixed $avatar
     */


    /**
     * @return mixed
     */
    public function getEmprunts()
    {
        return $this->emprunts;
    }

    /**
     * @param mixed $emprunts
     */
    public function setEmprunts($emprunts)
    {
        $this->emprunts = $emprunts;
    }

    /**
     * @return mixed
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * @param mixed $solde
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;
    }


}