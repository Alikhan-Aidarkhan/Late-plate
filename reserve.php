<?php
// reserve.php - receives the booking form from contact.html
//
// The "required" attributes in contact.html stop the browser from sending
// an empty form, but anyone can get around that, so the same checks run
// again here on the server. Never trust what arrives.

// turns < and > into plain text, so nobody can put their own HTML
// into our page through the form
function clean($value)
{
    return htmlspecialchars($value);
}

// read every field from the form
// ?? "" means: if the field was not sent at all, use an empty string
$name  = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$party = trim($_POST["party"] ?? "");
$date  = trim($_POST["date"] ?? "");
$time  = trim($_POST["time"] ?? "");
$notes = trim($_POST["notes"] ?? "");

// make a list of the required fields that are empty
$errors = [];
if ($name == "")  { $errors[] = "name"; }
if ($email == "") { $errors[] = "email"; }
if ($party == "") { $errors[] = "party size"; }
if ($date == "")  { $errors[] = "date"; }
if ($time == "")  { $errors[] = "time"; }

// "abc" is not empty, so check that the email looks like an email
if ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "a valid email";
}

// no errors: save the booking as one line in bookings.txt
if (count($errors) == 0) {
    $line = date("Y-m-d H:i") . " | " . $name . " | " . $email . " | " . $phone
          . " | party of " . $party . " | " . $date . " " . $time . " | " . $notes . "\n";
    file_put_contents("bookings.txt", $line, FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking &mdash; Late Plate</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="site-header">
    <a class="logo" href="index.html">
      <img src="images/logo.png" alt="Late Plate">
    </a>

    <nav class="site-nav">
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="menu.html">Menu</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="events.html">Events</a></li>
        <li><a href="faq.html">FAQ</a></li>
        <li><a class="active" href="contact.html">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main>
<?php if (count($errors) == 0) { ?>

    <h1>Thank you, <?php echo clean($name); ?>.</h1>
    <p>We have your request and will email you at
       <strong><?php echo clean($email); ?></strong> to confirm.</p>

    <table class="info-table">
      <caption>What you asked for. Reply to our email if anything is wrong.</caption>
      <tbody>
        <tr><td>Date</td><td><?php echo clean($date); ?></td></tr>
        <tr><td>Time</td><td><?php echo clean($time); ?></td></tr>
        <tr><td>Party size</td><td><?php echo clean($party); ?></td></tr>
        <tr><td>Phone</td><td><?php echo clean($phone); ?></td></tr>
        <tr><td>Notes</td><td><?php echo clean($notes); ?></td></tr>
      </tbody>
    </table>

    <p><a class="btn" href="index.html">Back to the home page</a></p>

<?php } else { ?>

    <h1>Something is missing.</h1>
    <p>Please fill in:</p>
    <ul>
<?php foreach ($errors as $field) { ?>
      <li><?php echo clean($field); ?></li>
<?php } ?>
    </ul>
    <p><a class="btn" href="contact.html">Back to the form</a></p>

<?php } ?>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 Late Plate, West Lafayette, IN</p>
  </footer>

</body>
</html>
