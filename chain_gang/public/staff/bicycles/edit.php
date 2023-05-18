<?php

require_once('../../../private/initialize.php');

require_login();

if (!isset($_GET['id'])) {
  redirect_to(url_for('/staff/bicycles/index.php'));
}

$id = $_GET['id'];
// display the form when we arent sending some post we want to the 
$bicycle = Bicycle::find_by_id($id);
if ($bicycle == false) {
  redirect_to(url_for('/staff/bicycles/index.php'));
}

if (is_post_request()) {

  // Save record using post parameters
  $args = $_POST['bicycle']; //html associative array alternative of getting post data
  // $args['brand'] = $_POST['brand'] ?? NULL;
  // $args['model'] = $_POST['model'] ?? NULL;
  // $args['year'] = $_POST['year'] ?? NULL;
  // $args['category'] = $_POST['category'] ?? NULL;
  //ETC

  //update the bike object with the form values and not the db values
  $bicycle->merge_attributes($args);
  //then save back those input values into the db
  $result = $bicycle->save();

  $result = false; ///idk
  if ($result === true) {
    $session->message('The bicycle was updated successfully.');
    redirect_to(url_for('/staff/bicycles/show.php?id=' . $id));
  } else {
    // show errors
  }
} else {

  // display the form when we arent sending some post we want to the 

}

?>

<?php $page_title = 'Edit Bicycle'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/bicycles/index.php'); ?>">&laquo; Back to List</a>

  <div class="bicycle edit">
    <h1>Edit Bicycle</h1>

    <?php echo display_errors($bicycle->errors); ?>

    <form action="<?php echo url_for('/staff/bicycles/edit.php?id=' . h(u($id))); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Edit Bicycle" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>