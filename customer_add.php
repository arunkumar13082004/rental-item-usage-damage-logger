<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold text-center">
                    <i class="bi bi-person-plus"></i> Add Customer
                </div>

                <div class="card-body">

                    <form method="POST">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Enter full name" required>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                   placeholder="Enter phone number" required>
                        </div>

                        <!-- Submit -->
                        <button type="submit" name="save_customer" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Save Customer
                        </button>

                    </form>

                </div>
            </div>

<?php
if (isset($_POST['save_customer'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO customers (name, phone)
            VALUES ('$name', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "
        <div class='alert alert-success mt-4 text-center'>
            <i class='bi bi-check-circle'></i> Customer saved successfully.
        </div>";
    } else {
        echo "
        <div class='alert alert-danger mt-4 text-center'>
            <i class='bi bi-exclamation-circle'></i> Phone number already exists.
        </div>";
    }
}
?>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
