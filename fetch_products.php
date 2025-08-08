<?php
require_once 'config.php';

header('Content-Type: application/json');

$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
mysqli_close($conn);
?>