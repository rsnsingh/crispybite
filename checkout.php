<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "DELETE FROM cart WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Checkout successful!"]);
?>