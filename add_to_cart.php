<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$user_id = $_SESSION['user_id'];
$item = $_POST['item'];
$price = $_POST['price'];

$sql = "SELECT * FROM cart WHERE user_id=? AND item_name=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $item);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id=? AND item_name=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $user_id, $item);
} else {
  $sql = "INSERT INTO cart (user_id, item_name, price, quantity) VALUES (?, ?, ?, 1)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isi", $user_id, $item, $price);
}
$stmt->execute();
echo json_encode(["success" => true]);
?>