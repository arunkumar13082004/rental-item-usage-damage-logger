<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm text-center">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="bi bi-database-check"></i> Database Status
                </div>

                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle-fill"></i>
                        Database connected successfully.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
