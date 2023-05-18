<?php

  ob_start(); // turn on output buffering

  // session_start(); // turn on sessions if needed

  // Assign file paths to PHP constants
  // __FILE__ returns the current path to this file
  // dirname() returns the path to the parent directory
  define("PRIVATE_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(PRIVATE_PATH));
  define("PUBLIC_PATH", PROJECT_PATH . '/public');
  define("SHARED_PATH", PRIVATE_PATH . '/shared');

  // Assign the root URL to a PHP constant
  // * Do not need to include the domain
  // * Use same document root as webserver
  // * Can set a hardcoded value:
  // define("WWW_ROOT", '/~kevinskoglund/chain_gang/public');
  // define("WWW_ROOT", '');
  // * Can dynamically find everything in URL up to "/public"
  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);

  require_once('functions.php');
  require_once('status_error_functions.php');
  require_once('db_credentials.php');
  require_once('database_functions.php');
  require_once('validation_functions.php');
  
  // Load class definitions manually

  
  //all classes in project directory(s) 
  //* wild card for the $file var
  //glob: Find pathnames matching a pattern
  //loads in all files from the directory with this loop on this array of
  //files. remember (are formatted as arrs we done this a lot lol) 
  //member the .wavs

  foreach(glob('classes/*.class.php') as $file)
  {
    require_once($file);
  }

  // Autoload class definitions
  function my_autoload($class)
  {
    //all word chars regex
    if(preg_match('/\A\w+\Z/', $class))
    {
      include('classes/' . $class . '.class.php');
    }
  }
  
  spl_autoload_register('my_autoload');


  //Heart of Connection 
  // (these methods and functions right here)
  //database connection value
  $database = db_connect();

  //DATABASE CLASS CONNECTION
  //setting this database value in the Class so it can  
  //easily be referred Globally by it's instances later on 
  DatabaseObject::set_database($database);

  $session = new Session;
  
?>
