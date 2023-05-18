<?php

class Bicycle extends DatabaseObject
{
    static protected $table_name = 'bicycles';

    static protected $db_columns = ['id', 'brand', 'model', 'year', 'category', 'color', 'gender', 'price', 'weight_kg', 'condition_id', 'description'];


    // ---PROPERTIES--- //
    public $id;
    public $brand;
    public $model;
    public $year;
    public $category;
    public $color;
    public $description;
    public $gender;
    public $price;
    public $weight_kg;
    public $condition_id;

    public const CATEGORIES = ['Road', 'Mountain', 'Hybrid', 'Cruiser', 'City', 'BMX'];

    public const GENDERS = ['Mens', 'Womens', 'Unisex'];

    public const CONDTION_OPTIONS = [
        1 => "Beat up",
        2 => "Decent",
        3 => "Good",
        4 => "Great",
        5 => "Like New",
    ];

    public function name()
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    public function __construct($args = [])
    {
        // $this->brand = isset($args['brand']) ? $args['brand'] : ''; Ternary way of doing this 
        $this->brand = $args['brand'] ?? ''; //BETTER
        $this->model = $args['model'] ?? '';
        $this->year = $args['year'] ?? '';
        $this->category = $args['category'] ?? '';
        $this->color = $args['color'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->gender = $args['gender'] ?? '';
        $this->price = $args['price'] ?? 0.00;
        $this->weight_kg = $args['weight_kg'] ?? 0.0;
        $this->condition_id = $args['condition_id'] ?? 3;

        /*common use case to POPULATE PROPERTIES
        allows private/protected properties to be set
        key => value 
        foreach ($args as $k => $v) 
        {
            if(property_exists($this, $k)){
                $this->$k = $v;
            }
        } */
    }

    //get/read that weight back 
    public function weight_kg()
    {
        //0.00 kg
        return number_format($this->weight_kg, 2) . " kg";
    }

    //set weight in kg
    public function set_weight_kg($value)
    {
        $this->weight_kg = floatval($value);
    }

    //get weight lbs
    public function weight_lbs()
    {
        $weight_lbs = floatval($this->weight_kg) * 2.2046226218;
        return number_format($weight_lbs, 2) . " lbs";
    }

    //set weight lbs
    public function set_weight_lbs($value)
    {
        $this->weight_kg = floatval($value / 2.2046226218);
    }

    public function condition()
    {
        //pretty much bind our condition_id with the index of out condition options assoc arr very nice
        if ($this->condition_id > 0) {
            return self::CONDTION_OPTIONS[$this->condition_id];
        } else {
            return "Unknown";
        }
    }

    protected function validate()
    {
        $this->errors = [];

        if (is_blank($this->brand)) {
            $this->errors[] = "Brand cannot be blank.";
        }
        if (is_blank($this->model)) {
            $this->errors[] = "Model cannot be blank.";
        }
        return $this->errors;
    }
}
 ?>