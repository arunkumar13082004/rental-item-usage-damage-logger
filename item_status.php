
<?php include "config/db.php";


$item_id = $_GET['id'];
$status = $_GET['status'];

$update_sql = "UPDATE items SET status = '$status' WHERE item_id = $item_id";
$conn->query($update_sql);

header("Location: inventory.php");
exit;


