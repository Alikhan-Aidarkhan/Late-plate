<?php
// reserve.php - receives the booking form from contact.html
//
// The JavaScript in js/main.js checks the fields before the form is sent,
// but a browser with JavaScript switched off would skip that, so the same
// checks run again here on the server. Never trust what arrives.

$required = ["name", "email", "party", "date", "time"];
$errors = [];
$data = [];

foreach ($required as $field) {
    // ?? "" means: use an empty string if the field was not sent at all
    $value = trim($_POST[$field] ?? "");
    if ($value === "") {
        $errors[] = $field;
    }
    $data[$field] = $value;
}

$data["phone"] = trim($_POST["phone"] ?? "");
$data["notes"] = trim($_POST["notes"] ?? "");

// a second check on the email, since "abc" passes the not-empty test
if ($data["email"] !== "" && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "email";
}

// htmlspecialchars turns < and > into harmless text, so nobody can push
// their own markup into the page through the form
function clean($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

if (empty($errors)) {
    // write the booking to a file next to this script
    $line = sprintf(
        "%s | %s | %s | party of %s | %s %s | %s | %s\n",
        date("Y-m-d H:i:s"),
        $data["name"],
        $data["email"],
        $data["party"],
        $data["date"],
        $data["time"],
        $data["phone"] !== "" ? $data["phone"] : "no phone",
        $data["notes"] !== "" ? str_replace("\n", " ", $data["notes"]) : "no notes"
    );
    file_put_contents(__DIR__ . "/bookings.txt", $line, FILE_APPEND | LOCK_EX);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo empty($errors) ? "Request received" : "Something is missing"; ?> &mdash; Late Plate</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;800&display=swap">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="site-header">
    <a class="logo" href="index.html">
      <img src="images/logo.png" alt="Late Plate">
    </a>

    <button class="burger" id="burger" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>

    <nav class="site-nav" id="siteNav">
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
<?php if (empty($errors)) { ?>

    <h1>Thank you, <?php echo clean($data["name"]); ?>.</h1>
    <p>We have your request and will email you at
       <strong><?php echo clean($data["email"]); ?></strong> to confirm.</p>

    <table class="hours-table">
      <caption>What you asked for. Reply to our email if anything is wrong.</caption>
      <tbody>
        <tr><th scope="row">Date</th><td><?php echo clean($data["date"]); ?></td></tr>
        <tr><th scope="row">Time</th><td><?php echo clean($data["time"]); ?></td></tr>
        <tr><th scope="row">Party size</th><td><?php echo clean($data["party"]); ?></td></tr>
        <tr><th scope="row">Phone</th><td><?php echo $data["phone"] !== "" ? clean($data["phone"]) : "&mdash;"; ?></td></tr>
        <tr><th scope="row">Notes</th><td><?php echo $data["notes"] !== "" ? nl2br(clean($data["notes"])) : "&mdash;"; ?></td></tr>
      </tbody>
    </table>

    <p><a class="btn" href="index.html">Back to the home page</a></p>

<?php } else { ?>

    <h1>Something is missing.</h1>
    <p>These fields still need filling in:</p>
    <ul>
<?php foreach ($errors as $field) { ?>
      <li><?php echo clean($field); ?></li>
<?php } ?>
    </ul>
    <p><a class="btn" href="contact.html">Back to the form</a></p>

<?php } ?>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 Late Plate &middot; 123 Northwestern Ave, West Lafayette, IN</p>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>
