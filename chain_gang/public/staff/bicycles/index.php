<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>

<?php
  
  
  //we get the current page from the url 
  //idea is when we click on a link that will go or jump to the
  //next page we want to using the ?page=2 url param 
  $current_page = $_GET['page'] ?? 1; //if there is no value then one
  $per_page = 5;
  $total_count = Bicycle::count_all();
  
  $pagination = new Pagination($current_page, $per_page, $total_count);
  // Find all bicycles;
  //use pagination instead
  // $bicycles = Bicycle::find_all();
  //call that sql get back rows of data then instanciate those fields and turn them into bicycles
  //array of bicycle of objects with the help of pagination
  $sql = "SELECT * FROM bicycles ";
  $sql .= "LIMIT {$per_page} ";
  $sql .= "OFFSET {$pagination->offset()}";
  $bicycles = Bicycle::find_by_sql($sql); 
  
?>
<?php $page_title = 'Bicycles'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="bicycles listing">
    <h1>Bicycles</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/bicycles/new.php'); ?>">Add Bicycle</a>
    </div>

  	<table class="list">
      <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Model</th>
        <th>Year</th>
        <th>Category</th>
        <th>Gender</th>
        <th>Color</th>
        <th>Price</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>

      <?php foreach($bicycles as $bicycle) { ?>
        <tr>
          <td><?php echo h($bicycle->id); ?></td>
          <td><?php echo h($bicycle->brand); ?></td>
          <td><?php echo h($bicycle->model); ?></td>
          <td><?php echo h($bicycle->year); ?></td>
          <td><?php echo h($bicycle->category); ?></td>
          <td><?php echo h($bicycle->gender); ?></td>
          <td><?php echo h($bicycle->color); ?></td>
          <td><?php 
          $price = $bicycle->price; //property assignment to this variable
          $formatted_price = '$' . number_format($price, 2, '.', ','); //adds comma at float thousandths 
          echo h($formatted_price) // noice; 
          ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/bicycles/show.php?id=' . h(u($bicycle->id))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/bicycles/edit.php?id=' . h(u($bicycle->id))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/bicycles/delete.php?id=' . h(u($bicycle->id))); ?>">Delete</a></td>
    	  </tr>
      <?php } ?>
  	</table>

    <?php 
    
    //has to be greater than one and not false to show this pagination arrow link
    $url = url_for('/staff/bicycles/index.php'); //helper const for this file for some in file pagination
    $pagination->page_links($url); //super method that displays all the page links in our pagination class

    ?>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
