<?php

/**
 * 
 * Login horror
 * 
**/
function loggedIn($user) {
    if (empty($user)) {
        $message = "You are not logged in.";
    } elseif ($user['role']) {
        $message = "You are logged in as user $user[name] with the role " . $user['role'];       
    } elseif ($user['name'] == 'admin' and $user['role'] == 'admin') {
        $message = "You are logged in as Super Admin";
    } else {
        $message = "You are logged in as user $user[name]";
    }

    // Aris version
    if (empty($user)) {     // till here all right. 
        $message = "You are not logged in."; // If the var "user" is not set that means the user is not logged in  
    } elseif ($user['name'] == 'admin' && $user['role'] == 'admin') { // here I made a little switch 
        /**
            when we want to filter by hierarchy we start from the top
            so first we have to check if the user is an Super Admin
            As I see in the code there is the role of the "Super Admin"
            To declare this role the we must have role='admin' and userName='admin'
            In my opinion for this case it would be better to have a new "role" type: "superAdmin"
            because our code is not very clear if we have to use also the "name" attr to declare a different role 
        **/
        /**
            also I changed the operator "and" to "&&"
            This is a known problem in php
            The operator "=" id higher than "and" so we have to use "()"
            The best practice is to avoid using the "AND"/"OR" operators and stick to the "&&"/"||"  
        **/  
        $message = "You are logged in as Super Admin";
    } elseif ($user['role'] == 'admin') {  // here I added the case the user is "simple Admin" and not "Super Admin" 
        $message = "You are logged in as Admin"; 
    } elseif ($user['role']) { // corrected some syntax error -> ".$user['name']." 
        $message = "You are logged in as user " . $user['name'] . " with the role " . $user['role'];        
    } else {  // corrected the same syntax error -> ".$user['name']."
        $message = "You are logged in as user " . $user['name'];
    }

    return $message;
}



/**
 * 
 * To wet to be dry
 * 
 * All the repeating methods are now in a mother Class "Animal"
 * And during the creation of the Chilled Class the number of legs is passed
 * (from constructor to constructor)
 * 
**/
class Animal {
    private $legs;

    public function __construct( $legs ) {
        $this->legs = $legs;
    }

    public function getLegs()
    {
        return $this->legs;
    }

    public function walk()
    {
        echo 'I am walking';
    }
} 

class Flamingo extends Animal {
    public function __construct( ) {
        parent::__construct( 2 ); // Assigns $legs to (motherClass) $this->legs
    }
}

class Mouse extends Animal {
    public function __construct( ) {
        parent::__construct( 4 ); // Assigns $legs to (motherClass) $this->legs
    }
}

class Cat extends Animal {
    public function __construct( ) {
        parent::__construct( 4 ); // Assigns $legs to (motherClass) $this->legs
    }
}

class Dog extends Animal {
    public function __construct( ) {
        parent::__construct( 4 ); // Assigns $legs to (motherClass) $this->legs
    }
}



/**
 * 
 * Only one please
 * 
 * 
**/
class ShoppingCart {
    protected $items = array();

    public function addToCart($item)
    {

    }
}