<?php include "partials/header.php"; ?>

<div class="container mt-4">

    <!-- Title -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">
            <i class="bi bi-box-seam"></i> Rental Item Usage & Damage Logger
        </h2>
        <p class="text-muted">
            Inspection-based rental tracking & damage control system
        </p>
    </div>

    <div class="row g-4">

        <!-- CUSTOMER -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                    <h5 class="card-title mt-2">Customer</h5>
                    <a href="customer_add.php" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-person-plus"></i> Add Customer
                    </a>
                    <a href="customer_profile.php" class="btn btn-outline-primary w-100">
                        <i class="bi bi-person-lines-fill"></i> Customer Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- INVENTORY -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-boxes fs-2 text-success"></i>
                    <h5 class="card-title mt-2">Inventory</h5>
                    <a href="item_add.php" class="btn btn-success w-100 mb-2">
                        <i class="bi bi-plus-square"></i> Add Item
                    </a>
                    <a href="inventory.php" class="btn btn-outline-success w-100 mb-2">
                        <i class="bi bi-list-ul"></i> Inventory List
                    </a>
                    <a href="item_history.php" class="btn btn-outline-success w-100">
                        <i class="bi bi-clock-history"></i> Item Health Timeline
                    </a>
                </div>
            </div>
        </div>

        <!-- RENTAL FLOW -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-repeat fs-2 text-warning"></i>
                    <h5 class="card-title mt-2">Rental Flow</h5>
                    <a href="checkout.php" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-cart-check"></i> Rental Checkout
                    </a>
                    <a href="active_rentals.php" class="btn btn-outline-warning w-100">
                        <i class="bi bi-arrow-return-left"></i> Active Rentals / Return
                    </a>
                </div>
            </div>
        </div>

        <!-- INSPECTION TEMPLATES -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check fs-2 text-info"></i>
                    <h5 class="card-title mt-2">Inspection Templates</h5>
                    <a href="inspection_templates.php" class="btn btn-info w-100">
                        <i class="bi bi-pencil-square"></i> Manage Templates
                    </a>
                </div>
            </div>
        </div>

        <!-- DAMAGE MANAGEMENT -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
                    <h5 class="card-title mt-2">Damage Management</h5>
                    <a href="damage_review.php" class="btn btn-danger w-100">
                        <i class="bi bi-shield-exclamation"></i> Damage Review & Override
                    </a>
                </div>
            </div>
        </div>

        <!-- SYSTEM -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-gear fs-2 text-secondary"></i>
                    <h5 class="card-title mt-2">System</h5>
                    <p class="text-muted small">
                        PHP + MySQL <br> Inspection-based tracking
                    </p>
                    <a href="test_db.php" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-database-check"></i> DB Health Check
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include "partials/footer.php"; ?>
