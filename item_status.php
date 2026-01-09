<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

<?php
$item_id = $_GET['id'] ?? null;
$new_status = $_GET['status'] ?? null;

if (!$item_id || !$new_status) {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
    include "partials/footer.php";
    exit;
}

// Fetch current item status
$item_result = $conn->query("
    SELECT status FROM items WHERE item_id = $item_id
");

if ($item_result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Item not found.</div>";
    include "partials/footer.php";
    exit;
}

$item = $item_result->fetch_assoc();

// 🚫 RULE: Do not allow status change if item is rented
if ($item['status'] === 'rented') {
    echo "
    <div class='alert alert-warning text-center'>
        <i class='bi bi-exclamation-triangle'></i>
        Cannot change status of an item that is currently rented.
    </div>";
    include "partials/footer.php";
    exit;
}

// Allow only valid statuses
$allowed_statuses = ['available', 'maintenance'];

if (!in_array($new_status, $allowed_statuses)) {
    echo "<div class='alert alert-danger'>Invalid status.</div>";
    include "partials/footer.php";
    exit;
}

// Safe update
$conn->query("
    UPDATE items
    SET status = '$new_status'
    WHERE item_id = $item_id
");

echo "
<div class='alert alert-success text-center'>
    Item status updated successfully.
</div>";

include "partials/footer.php";
