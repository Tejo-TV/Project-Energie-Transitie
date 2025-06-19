<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : class.php
// Omschrijving		  : Op deze pagina heb je alle classes van de appelicatie.
// Naam ontwikkelaar  : Tejo Veldman
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

class User {
    // Properties
    private $id;
    private $name;
    private $email;
    private $address_id;
    private $role_id;

    // Constructor
    public function __construct($id, $name, $email, $address_id, $role_id) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->address_id = $address_id;
        $this->role_id = $role_id;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAddressId() {
        return $this->address_id;
    }

    public function getRoleId() {
        return $this->role_id;
    }
}




?>