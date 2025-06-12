<?php
require('fpdf/fpdf.php');

// DB connection
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'crispy_bite';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
  die("Invalid order ID");
}

// Fetch order info
$order_sql = "SELECT * FROM orders WHERE id = $order_id";
$order_result = $conn->query($order_sql);
if ($order_result->num_rows == 0) {
  die("Order not found");
}
$order = $order_result->fetch_assoc();

// Fetch order items
$items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
$items_result = $conn->query($items_sql);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Header
$pdf->Cell(0,10,'Order Invoice',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Order Number: ' . $order['order_number'],0,1);
$pdf->Cell(0,10,'Order Date: ' . $order['order_date'],0,1);
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,10,'Item',1);
$pdf->Cell(30,10,'Price',1);
$pdf->Cell(30,10,'Qty',1);
$pdf->Cell(40,10,'Total',1);
$pdf->Ln();

// Items
$pdf->SetFont('Arial','',12);
$total = 0;
while($row = $items_result->fetch_assoc()) {
  $item_total = $row['price'] * $row['quantity'];
  $total += $item_total;

  $pdf->Cell(60,10,$row['item_name'],1);
  $pdf->Cell(30,10,number_format($row['price'], 2),1);
  $pdf->Cell(30,10,$row['quantity'],1);
  $pdf->Cell(40,10,number_format($item_total, 2),1);
  $pdf->Ln();
}

// Total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(120,10,'Total',1);
$pdf->Cell(40,10,number_format($total, 2),1);

// Output PDF
$pdf->Output("I", "invoice_" . $order['order_number'] . ".pdf");
?>
