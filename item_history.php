<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <!-- Search -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold text-center">
                    <i class="bi bi-clock-history"></i> Item Health Timeline
                </div>
                <div class="card-body">
                    <form method="GET">
                        <label class="form-label fw-bold">Item Code</label>
                        <div class="input-group">
                            <input type="text" name="code" class="form-control"
                                   placeholder="Enter item code (e.g., CAM001)" required>
                            <button class="btn btn-success">
                                <i class="bi bi-search"></i> View History
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
if (isset($_GET['code'])) {

    $code = $_GET['code'];

    $item_result = $conn->query("SELECT * FROM items WHERE item_code='$code'");

    if ($item_result->num_rows == 0) {
        echo "<div class='alert alert-danger text-center'>Item not found.</div>";
        include "partials/footer.php";
        exit;
    }

    $item = $item_result->fetch_assoc();
    $item_id = $item['item_id'];
?>

    <!-- Item Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-box-seam"></i> <?= $item['name'] ?>
                    </h5>
                    <p class="text-muted mb-0">
                        Item Code: <?= $item['item_code'] ?> | Category: <?= $item['category'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="row">
        <div class="col-md-12">

<?php
    $result = $conn->query("
        SELECT inspection_type, condition_json, inspected_at
        FROM rental_inspections ri
        JOIN rentals r ON ri.rental_id = r.rental_id
        WHERE r.item_id = $item_id
        ORDER BY inspected_at
    ");

    if ($result->num_rows == 0) {
        echo "<div class='alert alert-warning text-center'>No inspection history available.</div>";
    }

    while ($row = $result->fetch_assoc()) {

        $conditions = json_decode($row['condition_json'], true);
        $badge = ($row['inspection_type'] == 'checkout') ? 'primary' : 'danger';
?>

            <div class="card shadow-sm mb-3">
                <div class="card-header fw-bold">
                    <span class="badge bg-<?= $badge ?>">
                        <?= ucfirst($row['inspection_type']) ?>
                    </span>
                    <span class="text-muted ms-2">
                        <?= $row['inspected_at'] ?>
                    </span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <?php
                        foreach ($conditions as $key => $value) {

                            // Context-aware coloring
                                if ($key === 'working') {
                                    $statusColor = ($value === 'Yes') ? 'success' : 'danger';
                                } else {
                                    $statusColor = ($value === 'Yes') ? 'danger' : 'success';
                                }


                            echo "
                            <div class='col-md-3 mb-2'>
                                <span class='fw-bold'>" . ucfirst($key) . ":</span>
                                <span class='badge bg-$statusColor'>$value</span>
                            </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>

<?php } } ?>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
