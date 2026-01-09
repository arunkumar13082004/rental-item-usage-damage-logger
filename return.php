<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<?php
$rental_id = $_GET['rental_id'];

// Fetch rental details
$rental_result = $conn->query("SELECT * FROM rentals WHERE rental_id = $rental_id");
if ($rental_result->num_rows == 0) {
    echo "<div class='alert alert-danger text-center'>Invalid rental.</div>";
    include "partials/footer.php";
    exit;
}
$rental = $rental_result->fetch_assoc();

$customer_id = $rental['customer_id'];
$item_id     = $rental['item_id'];
$start_date  = $rental['start_date'];
$end_date    = $rental['end_date'];

// Fetch item pricing
$item = $conn->query("
    SELECT daily_rate, deposit_amount 
    FROM items 
    WHERE item_id = $item_id
")->fetch_assoc();

$daily_rate = (float)$item['daily_rate'];
$deposit    = (float)$item['deposit_amount'];

// Fetch checkout inspection
$checkout_row = $conn->query("
    SELECT condition_json 
    FROM rental_inspections
    WHERE rental_id = $rental_id 
      AND inspection_type = 'checkout'
")->fetch_assoc();

if (!$checkout_row) {
    echo "<div class='alert alert-danger text-center'>Checkout inspection not found.</div>";
    include "partials/footer.php";
    exit;
}

$checkout_conditions = json_decode($checkout_row['condition_json'], true);
?>

<div class="container mt-4">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-sm mb-4">
<div class="card-header bg-danger text-white fw-bold text-center">
    <i class="bi bi-arrow-return-left"></i> Return & Inspection
</div>

<div class="card-body">
<form method="POST">

<h5 class="fw-bold mb-3">
    <i class="bi bi-clipboard-check"></i> Condition Checklist
</h5>

<div class="row">
<?php
foreach ($checkout_conditions as $key => $value) {
    $badge = ($value == 'Yes') ? 'danger' : 'success';
    echo "
    <div class='col-md-4 mb-3'>
        <label class='form-label fw-bold'>
            " . ucfirst($key) . "
            <span class='badge bg-$badge ms-1'>Checkout: $value</span>
        </label>
        <select name='$key' class='form-select'>
            <option value='$value'>$value</option>
            <option value='" . ($value == 'Yes' ? 'No' : 'Yes') . "'>
                " . ($value == 'Yes' ? 'No' : 'Yes') . "
            </option>
        </select>
    </div>";
}
?>
</div>

<button type="submit" name="return_item" class="btn btn-danger w-100 mt-3">
    <i class="bi bi-check-circle"></i> Confirm Return
</button>

</form>
</div>
</div>

<?php
if (isset($_POST['return_item'])) {

    /* =========================
       1. SAVE RETURN INSPECTION
       ========================= */
    $return_conditions = [];
    foreach ($checkout_conditions as $key => $value) {
        $return_conditions[$key] = $_POST[$key];
    }

    $conn->query("
        INSERT INTO rental_inspections
        (rental_id, inspection_type, condition_json)
        VALUES ($rental_id, 'return', '" . json_encode($return_conditions) . "')
    ");

    /* =========================
       2. DAMAGE DETECTION
       ========================= */
    $damage_notes = [];
    foreach ($checkout_conditions as $key => $checkout_value) {
        if ($checkout_value != $return_conditions[$key]) {
            $damage_notes[] = "$key: $checkout_value → {$return_conditions[$key]}";
        }
    }
    $damage_fee = !empty($damage_notes) ? ($deposit * 0.20) : 0;

    /* =========================
       3. DATE CALCULATIONS
       ========================= */
    $today = date('Y-m-d');

    $rental_days = max(
        1,
        ceil((strtotime($today) - strtotime($start_date)) / 86400)
    );
    $base_charge = $rental_days * $daily_rate;

    $late_days = 0;
    $overdue_fee = 0;
    if (strtotime($today) > strtotime($end_date)) {
        $late_days = ceil(
            (strtotime($today) - strtotime($end_date)) / 86400
        );
        // 20% penalty PER late day (penalty only)
        $overdue_fee = $late_days * ($daily_rate * 0.20);
    }

    /* =========================
       4. FINAL TOTAL
       ========================= */
    $total_charge = $base_charge + $overdue_fee + $damage_fee;

    /* =========================
       5. SAVE BILLING (NO DUPLICATES)
       ========================= */
    $already_billed = $conn->query("
        SELECT claim_id FROM damage_claims WHERE rental_id = $rental_id
    ")->num_rows;

    if ($already_billed == 0) {
        $summary = [];
        $summary[] = "Base Rental: $rental_days day(s) × ₹$daily_rate = ₹$base_charge";
        if ($late_days > 0) {
            $summary[] = "Overdue Penalty: $late_days day(s) × 20% of ₹$daily_rate = ₹$overdue_fee";
        }
        if ($damage_fee > 0) {
            $summary[] = "Damage Fee: ₹$damage_fee";
        }

        $conn->query("
            INSERT INTO damage_claims
            (rental_id, customer_id, item_id, damage_summary, charge_amount)
            VALUES (
                $rental_id,
                $customer_id,
                $item_id,
                '" . implode(' | ', $summary) . "',
                $total_charge
            )
        ");
    }

    /* =========================
       6. CLOSE RENTAL
       ========================= */
    $conn->query("
        UPDATE rentals
        SET rental_status = 'returned', returned_at = NOW()
        WHERE rental_id = $rental_id
    ");

    // AUTO MAINTENANCE LOGIC
    if ($damage_fee > 0) {
        $conn->query("
            UPDATE items SET status = 'maintenance'
            WHERE item_id = $item_id
        ");
    } else {
        $conn->query("
            UPDATE items SET status = 'available'
            WHERE item_id = $item_id
        ");
    }

    /* =========================
       7. DISPLAY CHARGE BREAKDOWN
       ========================= */
    echo "
    <div class='alert alert-success'>
        <h5 class='fw-bold mb-3'>Charge Breakdown</h5>
        <table class='table table-bordered'>
            <tr>
                <th>Base Rental</th>
                <td>$rental_days day(s) × ₹$daily_rate</td>
                <td>₹$base_charge</td>
            </tr>";

    if ($late_days > 0) {
        echo "
            <tr>
                <th>Overdue Penalty</th>
                <td>$late_days day(s) × 20% of ₹$daily_rate</td>
                <td>₹$overdue_fee</td>
            </tr>";
    }

    if ($damage_fee > 0) {
        echo "
            <tr>
                <th>Damage Fee</th>
                <td>20% of deposit</td>
                <td>₹$damage_fee</td>
            </tr>";
    }

    echo "
            <tr class='table-success fw-bold'>
                <th colspan='2'>Total Charge</th>
                <th>₹$total_charge</th>
            </tr>
        </table>
    </div>";
}
?>

</div>
</div>
</div>

<?php include "partials/footer.php"; ?>
