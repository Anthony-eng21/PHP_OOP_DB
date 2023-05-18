<?php

require_once('../../../private/initialize.php');

require_login();

//build an associative arr from some post parameters
if(is_post_request()) {

  // Create record using post parameters
  $args = $_POST['bicycle'];
  // $args['brand'] = $_POST['brand'] ?? NULL;
  // $args['model'] = $_POST['model'] ?? NULL;
  // $args['year'] = $_POST['year'] ?? NULL;
  // $args['category'] = $_POST['category'] ?? NULL;
  // $args['color'] = $_POST['color'] ?? NULL;
  // $args['gender'] = $_POST['gender'] ?? NULL;
  // $args['price'] = $_POST['price'] ?? NULL;
  // $args['weight_kg'] = $_POST['weight_kg'] ?? NULL;
  // $args['condition_id'] = $_POST['condition_id'] ?? NULL;
  // $args['description'] = $_POST['description'] ?? NULL;

  //set the arguments as the same as post params
  $bicycle = new Bicycle($args);
  //create method that takes all those bicycle args and uses that obj data for the sql query
  $result = $bicycle->save();

  if($result === true) {
    $new_id = $bicycle->id; //bike id we found out in create();
    $session->message('The bicycle was created successfully.');
    redirect_to(url_for('/staff/bicycles/show.php?id=' . $new_id));
  } else {
    // show errors
  }

} else {
  // display the form need to instanciate here to have a value for these fields on this page
  $bicycle = new Bicycle;
}

?>

<?php $page_title = 'Create Bicycle'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/bicycles/index.php'); ?>">&laquo; Back to List</a>

  <div class="bicycle new">
    <h1>Create Bicycle</h1>

    <?php echo display_errors($bicycle->errors); ?>

    <form action="<?php echo url_for('/staff/bicycles/new.php'); ?>" method="post">

      <?php include('form_fields.php'); ?>
      
      <div id="operations">
        <input type="submit" value="Create Bicycle" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
