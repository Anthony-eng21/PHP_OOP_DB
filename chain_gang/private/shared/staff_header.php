<?php
global $session;
  if(!isset($page_title)) { $page_title = 'Staff Area'; }
?>

<!doctype html>

<html lang="en">
  <head>
    <title>Chain Gang - <?php echo h($page_title); ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/staff.css'); ?>" />
  </head>

  <body>
    <header>
      <h1>Chain Gang Staff Area</h1>
    </header>

    <navigation>
      <ul>
        <!-- Conditionally rendered conde associative with our global var $_SESSION -->
        <?php if ($session->is_logged_in()) { ?>
        <?php echo 'User: ' .  $session->username . '<br />'?>
        <li><a href="<?php echo url_for('/staff/index.php'); ?>">Menu</a></li>
        <li><a href="<?php echo url_for('/staff/logout.php'); ?>">Logout</a></li>
        <?php } ?>
      </ul>
    </navigation>

    <?php if($session->message()) {
      echo display_session_message();
    } ?>
