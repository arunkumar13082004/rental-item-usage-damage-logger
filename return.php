<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<?php
$rental_id = $_GET['rental_id'];

// Fetch rental details
$rental_sql = "SELECT * FROM rentals WHERE rental_id = $rental_id";
$rental_result = $conn->query($rental_sql);

if ($rental_result->num_rows == 0) {
    echo "<div class='alert alert-danger text-center'>Invalid rental.</div>";
    include "partials/footer.php";
    exit;
}

$rental = $rental_result->fetch_assoc();
$customer_id = $rental['customer_id'];
$item_id = $rental['item_id'];

// Fetch checkout inspection
$checkout_sql = "SELECT condition_json FROM rental_inspections 
                 WHERE rental_id = $rental_id AND inspection_type = 'checkout'";

$checkout_result = $conn->query($checkout_sql);

if ($checkout_result->num_rows == 0) {
    echo "<div class='alert alert-danger text-center'>Checkout inspection not found.</div>";
    include "partials/footer.php";
    exit;
}

$checkout_row = $checkout_result->fetch_assoc();
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
                                        <span class='badge bg-$badge ms-1'>
                                            Checkout: $value
                                        </span>
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

    // Save return inspection
    $return_conditions = [];
    foreach ($checkout_conditions as $key => $value) {
        $return_conditions[$key] = $_POST[$key];
    }

    $return_json = json_encode($return_conditions);

    $return_sql = "INSERT INTO rental_inspections
                   (rental_id, inspection_type, condition_json)
                   VALUES ($rental_id, 'return', '$return_json')";

    $conn->query($return_sql);

    echo "<div class='alert alert-success'>
            <i class='bi bi-save'></i> Return inspection saved.
          </div>";

    // Compare conditions
    $damage_list = [];

    foreach ($checkout_conditions as $key => $checkout_value) {
        $return_value = $return_conditions[$key];
        if ($checkout_value != $return_value) {
            $damage_list[] = "$key: $checkout_value → $return_value";
        }
    }

    if (count($damage_list) > 0) {

        echo "<div class='alert alert-danger'>
                <h6 class='fw-bold'><i class='bi bi-exclamation-triangle'></i> Damage Detected</h6>
                <ul>";
        foreach ($damage_list as $damage) {
            echo "<li>$damage</li>";
        }
        echo "</ul></div>";

        // Calculate damage charge
        $item = $conn->query("SELECT deposit_amount FROM items WHERE item_id = $item_id")->fetch_assoc();
        $charge_amount = $item['deposit_amount'] * 0.20;

        $damage_summary = implode(", ", $damage_list);

        $conn->query("
            INSERT INTO damage_claims
            (rental_id, customer_id, item_id, damage_summary, charge_amount)
            VALUES ($rental_id, $customer_id, $item_id, '$damage_summary', $charge_amount)
        ");

        echo "<div class='alert alert-warning'>
                <i class='bi bi-currency-rupee'></i>
                Damage charge applied: <b>₹$charge_amount</b>
              </div>";

    } else {
        echo "<div class='alert alert-success'>
                <i class='bi bi-shield-check'></i> No damage detected.
              </div>";
    }

    // Close rental
    $conn->query("
        UPDATE rentals 
        SET rental_status = 'returned', returned_at = NOW()
        WHERE rental_id = $rental_id
    ");

    $conn->query("
        UPDATE items 
        SET status = 'available'
        WHERE item_id = $item_id
    ");

    echo "<div class='alert alert-info fw-bold text-center'>
            <i class='bi bi-check2-circle'></i>
            Rental closed and item marked available.
          </div>";
}
?>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
