<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-warning fw-bold text-center">
                    <i class="bi bi-cart-check"></i> Rental Checkout
                </div>

                <div class="card-body">

                    <form method="POST">

                        <!-- Customer Phone -->
                        <div class="mb-3">
                            <label class="form-label">Customer Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <!-- Item Code -->
                        <div class="mb-3">
                            <label class="form-label">Item Code</label>
                            <input type="text" name="item_code" class="form-control" required>
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <hr>

                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-clipboard-check"></i> Condition Checklist (Checkout)
                        </h5>

                        <!-- Checklist -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Scratches</label>
                                <select name="scratches" class="form-select">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Missing Parts</label>
                                <select name="missing_parts" class="form-select">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Working</label>
                                <select name="working" class="form-select">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" name="checkout" class="btn btn-warning w-100 mt-3">
                            <i class="bi bi-check-circle"></i> Confirm Checkout
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php
/* ================= BACKEND LOGIC (UNCHANGED) ================= */

if (isset($_POST['checkout'])) {

    $phone = $_POST['phone'];

    // Step 1: Find customer
    $customer_sql = "SELECT * FROM customers WHERE phone = '$phone'";
    $customer_result = $conn->query($customer_sql);

    if ($customer_result->num_rows == 0) {
        echo "<div class='alert alert-danger mt-3'>Customer not found. Please add customer first.</div>";
        exit;
    }

    $customer = $customer_result->fetch_assoc();
    $customer_id = $customer['customer_id'];

    // Step 2: Find item
    $item_code = $_POST['item_code'];
    $item_sql = "SELECT * FROM items WHERE item_code = '$item_code'";
    $item_result = $conn->query($item_sql);

    if ($item_result->num_rows == 0) {
        echo "<div class='alert alert-danger mt-3'>Item not found.</div>";
        exit;
    }

    $item = $item_result->fetch_assoc();

    if ($item['status'] != 'available') {
        echo "<div class='alert alert-danger mt-3'>Item is not available for rent.</div>";
        exit;
    }

    $item_id = $item['item_id'];

    // Step 3: Create rental
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $rental_sql = "INSERT INTO rentals (customer_id, item_id, start_date, end_date)
                   VALUES ($customer_id, $item_id, '$start_date', '$end_date')";

    if (!$conn->query($rental_sql)) {
        echo "<div class='alert alert-danger mt-3'>Failed to create rental.</div>";
        exit;
    }

    $rental_id = $conn->insert_id;

    // Step 4: Save checkout inspection (template-based)
    $template_sql = "SELECT checklist_json FROM inspection_templates WHERE category = '{$item['category']}'";
    $template_result = $conn->query($template_sql);

    if ($template_result->num_rows > 0) {
        $template = $template_result->fetch_assoc();
        $inspection_array = json_decode($template['checklist_json'], true);

        foreach ($inspection_array as $key => $value) {
            if (isset($_POST[$key])) {
                $inspection_array[$key] = $_POST[$key];
            }
        }
    } else {
        $inspection_array = [
            "scratches" => $_POST['scratches'],
            "missing_parts" => $_POST['missing_parts'],
            "working" => $_POST['working']
        ];
    }

    $inspection_json = json_encode($inspection_array);

    $inspection_sql = "INSERT INTO rental_inspections
                       (rental_id, inspection_type, condition_json)
                       VALUES ($rental_id, 'checkout', '$inspection_json')";

    if (!$conn->query($inspection_sql)) {
        echo "<div class='alert alert-danger mt-3'>Failed to save inspection.</div>";
        exit;
    }

    // Step 5: Mark item rented
    $conn->query("UPDATE items SET status = 'rented' WHERE item_id = $item_id");

    echo "<div class='alert alert-success mt-3'>
            <i class='bi bi-check-circle'></i> Checkout completed successfully.
          </div>";
}
?>

<?php include "partials/footer.php"; ?>
