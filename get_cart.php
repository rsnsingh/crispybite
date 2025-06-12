<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM cart WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart = [];
while ($row = $result->fetch_assoc()) {
  $cart[] = $row;
}

echo json_encode($cart);
?>