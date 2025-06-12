<?php
// save_cart.php

// Database connection
$host = 'localhost';
$user = 'root';
$password = ''; // default in XAMPP
$db = 'crispy_bite'; // change to your actual database

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Read raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !is_array($data)) {
  http_response_code(400);
  echo "Invalid cart data";
  exit;
}

// Insert each item into the database
foreach ($data as $item => $details) {
  $stmt = $conn->prepare("INSERT INTO cart_items (item_name, price, quantity) VALUES (?, ?, ?)");
  $stmt->bind_param("sdi", $item, $details['price'], $details['quantity']);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
echo "Cart saved successfully!";
?>
