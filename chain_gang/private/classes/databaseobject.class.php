<?php
//expected errors in our project here!

class DatabaseObject
{
    static protected $database;
    static protected $table_name = "";
    static protected $columns = [];
    public $errors = [];

    // ------ Start of ACTIVE RECORD CODE ------ //

    //db connection right here heart of our project
    static public function set_database($database)
    {
        self::$database = $database;
    }

    //any find by sql satement we do it will execute the query and
    // confirm the query with this extra error handling
    //own function for meticulous task 
    static public function find_by_sql($sql)
    {
        $result = self::$database->query($sql);
        if (!$result) {
            exit("Database query failed.");
        }

        //results into objects
        $object_array = [];
        //loop through each property and field on a sql command 
        //allows us to manipulate our data easier as objects
        //in our active record design pattern
        while ($record = $result->fetch_assoc()) { //value of a returned arr
            //returns this record sql data as an object thanks to the instantiate method
            $object_array[] = static::instantiate($record);
        }

        $result->free(); //free the result mem lol

        return $object_array;
    }

    static public function find_all()
    {
        $sql = "SELECT * FROM " . static::$table_name;
        return static::find_by_sql($sql);
    }

    //GET COUNT OF WHOLE TABLE
    //1 row has 1 column = 1 value of count 
    //last set in the array then we want return it like this so its at the front of the array
    static public function count_all()
    {
        $sql = "SELECT COUNT(*) FROM " . static::$table_name;
        $result_set = self::$database->query($sql);
        $row = $result_set->fetch_row();
        return $row[0];
    }


    static public function find_by_id($id)
    {
        //dynamic values for our sql in php look like this id/field=" '" . val . "' "
        $sql = "SELECT * FROM " . static::$table_name . " ";
        $sql .= "WHERE id='" . self::$database->escape_string($id) . "'";
        $obj_array = static::find_by_sql($sql); //compares and returns our db fields into objects fields on instanciatian
        //RETURN AN OBJECT || FALSE
        if (!empty($obj_array)) {
            return array_shift($obj_array); //pulls the object we need off the front of the set 
        } else {
            return mysqli_error(self::$database);
        }
    }



    //CONVERTS THE VALUES IN A ROW INTO A NEW OBJECT WITH PROPERTIES WITH THE SAME VALUES
    //loop through all of the different collumns in a row that we pull back
    //and checks if the collumns exist as a property on this object 
    //then assign a value
    static protected function instantiate($record)
    {
        $object = new static;
        //could manually assign values to properties
        // but auto assign easier and reusable
        foreach ($record as $property => $value) {
            if (property_exists($object, $property)) {
                //dynamically setting these properties thats why we use double $ obj reference
                $object->$property = $value;
            }
        }
        return $object;
    }

    //simple validation logic
    protected function validate()
    {
        $this->errors = [];

        //add custom validations for the subc
        return $this->errors;
    }

    //TRANSFORM OUR OBJ POST PARAM PROP DATA INTO AN SQL INSERT STATEMENT
    //Using a dynamic list of values to set these fields dynamically so we can reuse this
    protected function create()
    {
        $this->validate(); //check validations arr
        //had errors wasnt able to be created
        if (!empty($this->errors)) {
            return false;
        }

        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . static::$table_name . " (";
        $sql .= join(', ', array_keys($attributes)); // key 
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes)); //value
        // $sql .= "'" . $this->brand . "', ";
        // $sql .= "'" . $this->model . "', ";
        // etc
        $sql .= "')";
        $result = self::$database->query($sql);
        if ($result) { //sets the last objects insert id so we can track these inserted data sets
            $this->id = self::$database->insert_id;
        }
        return $result;
    }

    protected function update()
    {
        $this->validate(); //check validations arr
        //had errors wasnt able to be created
        if (!empty($this->errors)) {
            return false;
        }

        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . static::$table_name . " SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE id='" . self::$database->escape_string($this->id) . "'";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        return $result;
    }

    //sync update and create into one function with some conditional logic
    //new record has no id yet 
    public function save()
    {
        if (isset($this->id)) { //existing record
            return $this->update();
        } else {
            return $this->create(); //no id / incoming record
        }
    }

    // get the existing attributes and get the updated obj in memory to update the db's data for this mirrored obj data
    public function merge_attributes($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    //PROPERTIES WHICH HAVE ALL THE DB COLUMNS CEPT FOR ID/AUTO INCREMENTED FIELD
    //make an ASSOC ARR  with the key value property and values we want in our insert/create() statement
    public function attributes()
    {
        $attributes = [];
        //loop through all the columns
        foreach (static::$db_columns as $column) {
            if ($column == 'id') {
                continue;
            }
            //dynamic object reference have double $
            //assign the key value for these columns to match our object in our sql insert statement
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    //helpful fn() to sanitize our SQL Bike creation logic
    protected function sanitized_attributes()
    {
        $sanitized = [];
        foreach ($this->attributes() as $key => $value) {
            $sanitized[$key] = self::$database->escape_string($value); //lets us sanitize and escape string on the sql insertions
        }
        return $sanitized;
    }

    //
    public function delete()
    {
        $sql = "DELETE FROM " . static::$table_name . " ";
        //sanitize value and drop it in
        $sql .= "WHERE id='" . self::$database->escape_string($this->id) . "' ";
        $sql .= "LIMIT 1";
        $result = self::$database->query($sql);
        return $result;

        //after deleting the instance of the object will 
        //still exist even though the db record doesnt.
        //this can be useful, as in;
        //  echo  $user->first_name . " was deleted."'
        //but for example we call call $user->update() after calling $user->delete
    }

    // ------ END OF ACTIVE RECORED CODE ------ //
}

?>