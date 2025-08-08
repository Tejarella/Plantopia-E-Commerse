<?php
require_once 'config.php';

header('Content-Type: application/json');

$session_id = $_SESSION['session_id'];
$count = getCartCount($conn, $session_id);

echo json_encode(['count' => $count]);
mysqli_close($conn);
?>
