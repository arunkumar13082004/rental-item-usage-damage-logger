<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <!-- Search Card -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold text-center">
                    <i class="bi bi-person-search"></i> Customer Profile
                </div>
                <div class="card-body">
                    <form method="GET">
                        <label class="form-label">Customer Phone</label>
                        <div class="input-group">
                            <input type="text" name="phone" class="form-control" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> View
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
if (isset($_GET['phone'])) {

    $phone = $_GET['phone'];

    // Step 1: Find customer
    $customer_sql = "SELECT * FROM customers WHERE phone = '$phone'";
    $customer_result = $conn->query($customer_sql);

    if ($customer_result->num_rows == 0) {
        echo "<div class='alert alert-danger text-center'>Customer not found.</div>";
        include "partials/footer.php";
        exit;
    }

    $customer = $customer_result->fetch_assoc();
    $customer_id = $customer['customer_id'];
?>

    <!-- Customer Details -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">
                        <i class="bi bi-person-circle"></i> <?= $customer['name'] ?>
                    </h5>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-telephone"></i> <?= $customer['phone'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rental History -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    <i class="bi bi-clock-history"></i> Rental History
                </div>
                <div class="card-body">

<?php
    $rental_sql = "
        SELECT r.rental_id, r.start_date, r.end_date, r.rental_status, i.name AS item_name
        FROM rentals r
        JOIN items i ON r.item_id = i.item_id
        WHERE r.customer_id = $customer_id
        ORDER BY r.start_date DESC
    ";

    $rental_result = $conn->query($rental_sql);

    if ($rental_result->num_rows == 0) {
        echo "<p class='text-muted'>No rentals found.</p>";
    } else {
        echo "
        <table class='table table-striped table-hover'>
            <thead class='table-light'>
                <tr>
                    <th>Rental ID</th>
                    <th>Item</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";
        
        while ($row = $rental_result->fetch_assoc()) {

            $statusClass = match ($row['rental_status']) {
                'active' => 'warning',
                'returned' => 'success',
                default => 'secondary'
            };

            echo "
                <tr>
                    <td>{$row['rental_id']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['start_date']}</td>
                    <td>{$row['end_date']}</td>
                    <td>
                        <span class='badge bg-$statusClass'>
                            {$row['rental_status']}
                        </span>
                    </td>
                </tr>";
        }

        echo "</tbody></table>";
    }
?>

                </div>
            </div>
        </div>
    </div>

    <!-- Damage & Risk -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    <i class="bi bi-exclamation-triangle"></i> Damage Summary
                </div>
                <div class="card-body">

<?php
    $damage_sql = "
        SELECT COUNT(*) AS damage_count,
               IFNULL(SUM(charge_amount), 0) AS total_charge
        FROM damage_claims
        WHERE customer_id = $customer_id
    ";

    $damage = $conn->query($damage_sql)->fetch_assoc();
    $damage_count = $damage['damage_count'];
    $total_charge = $damage['total_charge'];

    echo "<p>Total Damage Incidents: <b>$damage_count</b></p>";
    echo "<p>Total Charges: <b>₹$total_charge</b></p>";
?>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    <i class="bi bi-shield-check"></i> Risk Level
                </div>
                <div class="card-body text-center">

<?php
    if ($damage_count == 0) {
        $risk = "Low Risk";
        $color = "success";
    } elseif ($damage_count <= 2) {
        $risk = "Medium Risk";
        $color = "warning";
    } else {
        $risk = "High Risk";
        $color = "danger";
    }

    echo "<span class='badge bg-$color fs-5 px-4 py-2'>$risk</span>";
?>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

</div>

<?php include "partials/footer.php"; ?>
