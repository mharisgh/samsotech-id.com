<?php
include '../includes/connections.php';
/**
 * Requires the "PHP Email Form" library
 * The "PHP Email Form" library is available only in the pro version of the template
 * The library should be uploaded to: vendor/php-email-form/php-email-form.php
 * For more info and help: https://bootstrapmade.com/php-email-form/
 */
// Replace contact@example.com with your real receiving email address
$get_email = get_where_cond("tbl_contact_main", "id=1");
$res_email = $get_email->fetch_assoc();
$receiving_email_address = $res_email['email'];

if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include( $php_email_form );
}
else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = $_POST['name'];
$contact->designation = $_POST['designation'];
$contact->from_email = $_POST['email'];
$contact->phone = $_POST['phone'];
$contact->company = $_POST['company'];
$contact->subject = $_POST['subject'];

// Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
/*
  $contact->smtp = array(
  'host' => 'example.com',
  'username' => 'example',
  'password' => 'pass',
  'port' => '587'
  );
 */

$contact->add_message($_POST['name'] . " (" . $_POST['designation'] . ")", 'From');
$contact->add_message($_POST['email'], 'Email');
$contact->add_message($_POST['phone'], 'Phone');
$contact->add_message($_POST['company'], 'Company');
$contact->add_message($_POST['message'], 'Message', 10);

echo $contact->send();
?>
