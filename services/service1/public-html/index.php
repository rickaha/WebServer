<?php
session_start();
// Generate CSRF token and store in session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="css/pico.min.css">
    <title>Example1</title>
  </head>
  <body>
    <main class="container">
      <h1>Welcome to example1.se</h1>
    </main>
  </body>
