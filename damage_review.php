<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white fw-bold text-center">
                    <i class="bi bi-exclamation-triangle"></i> Damage Review & Override
                </div>
            </div>

<?php
$result = $conn->query("
    SELECT d.*, c.name AS customer, i.name AS item
    FROM damage_claims d
    JOIN customers c ON d.customer_id = c.customer_id
    JOIN items i ON d.item_id = i.item_id
");

if ($result->num_rows == 0) {
    echo "
    <div class='alert alert-success text-center'>
        <i class='bi bi-check-circle'></i> No damage claims pending.
    </div>";
}

while ($row = $result->fetch_assoc()) {
?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-2">
                        <i class="bi bi-person-circle"></i> <?= $row['customer'] ?>
                    </h5>

                    <p class="mb-1">
                        <b>Item:</b> <?= $row['item'] ?>
                    </p>

                    <div class="alert alert-danger">
                        <b>Damage:</b> <?= $row['damage_summary'] ?>
                    </div>

                    <p>
                        <b>Current Charge:</b> ₹<?= $row['charge_amount'] ?>
                    </p>

                    <form method="POST" class="row g-2 align-items-center">

                        <input type="hidden" name="id" value="<?= $row['claim_id'] ?>">

                        <div class="col-md-4">
                            <input type="number" name="charge" class="form-control"
                                   value="<?= $row['charge_amount'] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="note" class="form-control"
                                   placeholder="Admin note (optional)">
                        </div>

                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" name="update" class="btn btn-success w-100">
                                <i class="bi bi-save"></i> Update
                            </button>

                            <button type="submit" name="waive" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle"></i> Waive
                            </button>
                        </div>

                    </form>

                </div>
            </div>

<?php } ?>

        </div>
    </div>
</div>

<?php
/* ===== BACKEND LOGIC (UNCHANGED) ===== */

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $charge = $_POST['charge'];

    $conn->query("
        UPDATE damage_claims 
        SET charge_amount = $charge 
        WHERE claim_id = $id
    ");
}

if (isset($_POST['waive'])) {
    $id = $_POST['id'];
    $conn->query("
        DELETE FROM damage_claims 
        WHERE claim_id = $id
    ");
}
?>

<?php include "partials/footer.php"; ?>
