<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white fw-bold text-center">
                    <i class="bi bi-clipboard-check"></i> Inspection Templates
                </div>

                <div class="card-body">

                    <p class="text-muted small mb-4 text-center">
                        Define checklist templates based on item category.
                        These will be used automatically during checkout and return.
                    </p>

                    <form method="POST">

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Item Category</label>
                            <input type="text" name="category" class="form-control"
                                   placeholder="Camera, Laptop, Tool, etc."
                                   required>
                        </div>

                        <!-- Checklist -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Checklist Fields</label>
                            <input type="text" name="checklist" class="form-control"
                                   placeholder="scratches,missing_parts,working"
                                   required>
                            <div class="form-text">
                                Enter comma-separated condition fields.  
                                Example: scratches, lens_damage, working
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" name="save" class="btn btn-info w-100">
                            <i class="bi bi-save"></i> Save Template
                        </button>

                    </form>

                </div>
            </div>

<?php
if (isset($_POST['save'])) {

    $category = $_POST['category'];
    $items = explode(",", $_POST['checklist']);

    $checklist = [];
    foreach ($items as $item) {
        $checklist[trim($item)] = "No";
    }

    $json = json_encode($checklist);

    $sql = "INSERT INTO inspection_templates (category, checklist_json)
            VALUES ('$category', '$json')";

    $conn->query($sql);

    echo "
    <div class='alert alert-success mt-4 text-center'>
        <i class='bi bi-check-circle'></i> Inspection template saved successfully.
    </div>";
}
?>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
