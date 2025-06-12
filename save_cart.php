<?php
// save_cart.php

$host = 'localhost';
$user = 'root';
$password = ''; // XAMPP default
$db = 'crispy_bite';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !is_array($data)) {
  http_response_code(400);
  echo "Invalid cart data";
  exit;
}

// Generate a unique order number (you can format this differently)
$order_number = uniqid("ORD-");

// Step 1: Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (order_number) VALUES (?)");
$stmt->bind_param("s", $order_number);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Step 2: Insert each item under this order
foreach ($data as $item => $details) {
  $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, price, quantity) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isdi", $order_id, $item, $details['price'], $details['quantity']);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
//echo "Order placed successfully. Order Number: " . $order_number;

echo json_encode([
  "status" => "success",
  "message" => "Order placed successfully",
  "order_id" => $order_id,
  "order_number" => $order_number
]);


?>
