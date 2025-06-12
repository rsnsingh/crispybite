<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'crispy_bite';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
  die("Connection failed");
}

$order_id = intval($_GET['order_id'] ?? 0);
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
if (!$order) {
  echo "Order not found.";
  exit;
}

echo "<h3>Order: " . $order['order_number'] . "</h3>";
echo "<p>Date: " . $order['order_date'] . "</p>";

$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
echo "<table border='1' cellpadding='5'><tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th></tr>";
$total = 0;
while($row = $items->fetch_assoc()) {
  $line = $row['price'] * $row['quantity'];
  $total += $line;
  echo "<tr><td>{$row['item_name']}</td><td>{$row['price']}</td><td>{$row['quantity']}</td><td>$line</td></tr>";
}
echo "<tr><td colspan='3'><strong>Total</strong></td><td><strong>$total</strong></td></tr></table>";

$conn->close();
?>
