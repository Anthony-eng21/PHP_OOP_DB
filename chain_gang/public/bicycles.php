<?php require_once('../private/initialize.php'); ?>

<?php $page_title = 'Inventory'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <div id="page">
    <div class="intro">
      <img class="inset" src="<?php echo url_for('/images/AdobeStock_55807979_thumb.jpeg') ?>" />
      <h2>Our Inventory of Used Bicycles</h2>
      <p>Choose the bike you love.</p>
      <p>We will deliver it to your door and let you try it before you buy it.</p>
    </div>

    <table id="inventory">
      <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Year</th>
        <th>Category</th>
        <th>Gender</th>
        <th>Color</th>
        <th>Weight</th>
        <th>Condition</th>
        <th>Price</th>
        <th>&nbsp;</th>
      </tr>

<?php

//USING OUR DATABASE DATA 

//finds all our bicycles sql method
$bikes = Bicycle::find_all();

//IMPORTANT!
//INSTANCIATING CSV VALUES INTO HTML WITH PHP HELP;
//the file we are parsing our csv file then get back our bike array/data from csv then loop (foreach) 
//through them in our html instanciate the bicycle then read the properties from there
// $parser = new ParseCSV(PRIVATE_PATH . '/used_bicycles.csv');
// $bike_array = $parser->parse();

// print_r($bike_array);

// $args = ['brand' => 'Trek', 'model' => 'Emonda', 'year' => 2017, 'gender' => 'Unisex', 'color' => 'black', 'category' => 'Road', 'weight_kg' => 1.5, 'price' => 1000.00];
// $bike = new Bicycle($args);

?>
  <?php foreach($bikes as $bike) { //set of arguments in designed to be assoc values?> 
    <?php // CSV CODE : $bike = new Bicycle($args); //takes the args from the parsed csv and makes our arguments ?> 
    <tr>
      <td><?php echo h($bike->brand); ?></td>
      <td><?php echo h($bike->model); ?></td>
      <td><?php echo h($bike->year); ?></td>
      <td><?php echo h($bike->category); ?></td>
      <td><?php echo h($bike->gender); ?></td>
      <td><?php echo h($bike->color); ?></td>
      <td><?php echo h($bike->weight_kg()) . ' / ' . h($bike->weight_lbs()); ?></td>
      <td><?php echo h($bike->condition()); ?></td>
      <td>
        <?php
          $price = $bike->price; //property assignment to this variable
          $formatted_price = '$' . number_format($price, 2, '.', ','); //adds comma at float thousandths 
          echo h($formatted_price) // noice
        ?>
      </td>
      <!-- Wow super easy to set dynamic routes with this design pattern -->
      <td><a href="detail.php?id=<?php echo $bike->id ?>">View</a></td>
    </tr>
  <?php } ?>

    </table>

  <?php
  ?>
  </div>


</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
