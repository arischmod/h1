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
 * To implement this I used the Singleto Desighn pattern
 * https://en.wikipedia.org/wiki/Singleton_pattern
 * 
 * In the rest of our code to create a new ShopingCart
 * we will call ShoppingCart::getInstance();
 * 
 * No mater how many times we will call "ShoppingCart::getInstance()"
 * the same object will be retured
 * 
**/
class ShoppingCart {

    private static $instance = null; // the one and only instance of ShoppingCart
    protected $items = array();

    // private constructor so we prevent the initiation from any other part of our code
    private function __construct() {

    }

    // the one-and-only ShoppingCart instance will be created from within the class itself
    // if it hasn't been already
    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new ShoppingCart();

        return self::$instance;
    }

    public function addToCart($item)
    {

    }
}



/**
 * 
 * Hold the door
 * 
 * the use of modulo operator is a very standar way to find a numbers multiples
 * 
**/
for($i = 1; $i <= 100; $i++) {    
    
    if ($i%3==0 && $i%5==0) {
        echo  "HODOR";
        echo "<br>";  
    }
    else if ($i % 3 == 0) {
        echo  "HO";
        echo "<br>";  
    }
    else if ($i % 5 == 0) {
        echo  "DOR";
        echo "<br>";  
    }
    else {
        echo  $i;
        echo "<br>";
    }
}


/**
 * 
 * The lowercase is a lie
 * 
 * 
**/
$a = array(
    'A' => 'abcd',
    'B' => 'qwerty',
    'C' => array(
        'X' => 'aaaa',
        'Y' => 'bbbb'
    )
);


function makeUper(&$value, $key) // TIP! '&$value' we pass the variable by reference in the function so the function can realy modify it
{
    $value = strtoupper($value);
}

array_walk_recursive($a, 'makeUper');


/**
 * 
 * Graceful replacement
 * 
 * 
**/
$colors = array('red', 'white', 'blue');

echo "When the sun sets on the pristine ". $colors[1] ." beach it turns the ". $colors[2] ." ocean into a fury ". $colors[0] ." pool of lava.";



/**
 * 
 * Palindrome
 * 
 * 
**/
function isPlaindrome($string) {
    
    //remove spaces
    $string = str_replace(' ', '', $string);

    //remove special characters
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

    //make all lowercase
    $string = strtolower($string);

    if (strrev($string) == $string) {   // compare with reversed string
        echo("Is Palindrome!!!");
    } 
    else {
        echo("not Palindrome...");
    }
}

$string = "No 'x' in Nixon";
isPlaindrome($string);


/**
 * 
 * That one colleague
 * 
 * 
**/
// mysqldump -u mysqldump -u example -p employees > arisDump.sql

