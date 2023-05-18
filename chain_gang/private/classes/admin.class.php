<?php

class Admin extends DatabaseObject {

  static protected $table_name = "admins";
  static protected $db_columns = ['id', 'first_name', 'last_name', 'email', 'username', 'hashed_password'];

  public $id;
  public $first_name;
  public $last_name;
  public $email;
  public $username;
  protected $hashed_password;
  public $password;
  public $confirm_password;
  protected $password_required = true;

  public function __construct($args=[]) {
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->username = $args['username'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
  }

  public function full_name() {
    return $this->first_name . " " . $this->last_name;
  }

  protected function set_hashed_password()
  {
    //call this to do the encryption of our hashed password
    $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function verify_password($password)
  {
    //built in handy passsword to use the same algo to compare the incoming pw against the hashed password 
    return password_verify($password, $this->hashed_password);
  }

  //HASH PASSWORD THEN CALL ORIGINAL VERSION
  //does own logic under create for preprocessing. then do the parent function for create
  protected function create()
  {
    $this->set_hashed_password();
    return parent::create();
  }

  protected function update()
  {
    if($this->password != "") {
      //validate pw
      $this->set_hashed_password();
    } else {
      //password not being updated onupdate / skip hashing and validation 
      $this->password_required = false;
    }
    return parent::update();
  }

protected function validate() 
{
  $this->errors = [];

  if(is_blank($this->first_name)) {
    $this->errors[] = "First name cannot be blank.";
  } elseif (!has_length($this->first_name, array('min' => 2, 'max' => 255))) {
    $this->errors[] = "First name must be between 2 and 255 characters.";
  }

  if(is_blank($this->last_name)) {
    $this->errors[] = "Last name cannot be blank.";
  } elseif (!has_length($this->last_name, array('min' => 2, 'max' => 255))) {
    $this->errors[] = "Last name must be between 2 and 255 characters.";
  }

  if(is_blank($this->email)) {
    $this->errors[] = "Email cannot be blank.";
  } elseif (!has_length($this->email, array('max' => 255))) {
    $this->errors[] = "Last name must be less than 255 characters.";
  } elseif (!has_valid_email_format($this->email)) {
    $this->errors[] = "Email must be a valid format.";
  }

  if(is_blank($this->username)) {
    $this->errors[] = "Username cannot be blank.";
  } elseif (!has_length($this->username, array('min' => 8, 'max' => 255))) {
    $this->errors[] = "Username must be between 8 and 255 characters.";
  } elseif(!has_unique_username($this->username, $this->id ?? 0)) {
    $this->errors[] = "Username not allowed try another.";
  }

if($this->password){
  if(is_blank($this->password)) {
    $this->errors[] = "Password cannot be blank.";
  } elseif (!has_length($this->password, array('min' => 12))) {
    $this->errors[] = "Password must contain 12 or more characters";
  } elseif (!preg_match('/[A-Z]/', $this->password)) {
    $this->errors[] = "Password must contain at least 1 uppercase letter";
  } elseif (!preg_match('/[a-z]/', $this->password)) {
    $this->errors[] = "Password must contain at least 1 lowercase letter";
  } elseif (!preg_match('/[0-9]/', $this->password)) {
    $this->errors[] = "Password must contain at least 1 number";
  } 
  // elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
  //   $this->errors[] = "Password must contain at least 1 symbol";
  // }

  //Very nice
  if(is_blank($this->confirm_password)) {
    $this->errors[] = "Confirm password cannot be blank.";
  } elseif ($this->password !== $this->confirm_password) {
    $this->errors[] = "Password and confirm password must match.";
  }

}
  return $this->errors;
}

static public function find_by_username($username)
{
  $sql = "SELECT * FROM " . static::$table_name . " ";
  $sql .= "WHERE username='" . self::$database->escape_string($username) . "'";
  $obj_array = static::find_by_sql($sql); //compares and returns our db fields into objects fields on instanciatian
  //RETURN AN OBJECT || FALSE
  if (!empty($obj_array)) {
      return array_shift($obj_array); //pulls the object we need off the front of the set 
  } else {
      return mysqli_error(self::$database);
  }
}

}

?>
