<?php

//handy handy
function require_login()
{
  global $session;
  if (!$session->is_logged_in()) {
    redirect_to(url_for('/staff/login.php'));
  } else {
    //Do Nothing let rest of page proceeds
  }
}

function display_errors($errors=array()) {
  $output = '';
  if(!empty($errors)) {
    $output .= "<div class=\"errors\">";
    $output .= "Please fix the following errors:";
    $output .= "<ul>";
    foreach($errors as $error) {
      $output .= "<li>" . h($error) . "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

function display_session_message() {
  global $session;
  $msg = $session->message();
  $session->clear_message();
  return '<div id="message">' . h($msg) . '</div>';
}

?>
