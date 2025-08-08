<?php
require_once 'config.php';

header('Content-Type: application/json');

$session_id = $_SESSION['session_id'];
$total = getCartTotal($conn, $session_id);

echo json_encode(['total' => number_format($total, 2)]);
mysqli_close($conn);
?>