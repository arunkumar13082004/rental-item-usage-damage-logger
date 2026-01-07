<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold text-center">
                    <i class="bi bi-plus-square"></i> Add Rental Item
                </div>

                <div class="card-body">

                    <form method="POST">

                        <!-- Item Code -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Item Code</label>
                            <input type="text" name="item_code" class="form-control"
                                   placeholder="Unique item code (e.g., CAM001)"
                                   required>
                        </div>

                        <!-- Item Name -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Item Name</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Item name"
                                   required>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <input type="text" name="category" class="form-control"
                                   placeholder="Camera, Laptop, Tool, etc."
                                   required>
                        </div>

                        <div class="row">
                            <!-- Deposit -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Deposit Amount (₹)</label>
                                <input type="number" step="0.01" name="deposit_amount"
                                       class="form-control" required>
                            </div>

                            <!-- Daily Rate -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Daily Rate (₹)</label>
                                <input type="number" step="0.01" name="daily_rate"
                                       class="form-control" required>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" name="save_item" class="btn btn-success w-100">
                            <i class="bi bi-save"></i> Save Item
                        </button>

                    </form>

                </div>
            </div>

<?php
if (isset($_POST['save_item'])) {

    $item_code = $_POST['item_code'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $deposit = $_POST['deposit_amount'];
    $rate = $_POST['daily_rate'];

    $sql = "INSERT INTO items
            (item_code, name, category, deposit_amount, daily_rate)
            VALUES
            ('$item_code', '$name', '$category', '$deposit', '$rate')";

    if ($conn->query($sql) === TRUE) {
        echo "
        <div class='alert alert-success mt-4 text-center'>
            <i class='bi bi-check-circle'></i> Item added successfully.
        </div>";
    } else {
        echo "
        <div class='alert alert-danger mt-4 text-center'>
            <i class='bi bi-exclamation-circle'></i> Item code already exists.
        </div>";
    }
}
?>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
