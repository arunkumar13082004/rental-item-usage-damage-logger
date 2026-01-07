<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Item Usage & Damage Logger</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="bi bi-box-seam"></i> Rental Logger
        </a>

        <!-- Toggle (Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <!-- Customers -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-people"></i> Customers
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="customer_add.php">Add Customer</a></li>
                        <li><a class="dropdown-item" href="customer_profile.php">Customer Profile</a></li>
                    </ul>
                </li>

                <!-- Inventory -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-boxes"></i> Inventory
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="item_add.php">Add Item</a></li>
                        <li><a class="dropdown-item" href="inventory.php">Inventory List</a></li>
                        <li><a class="dropdown-item" href="item_history.php">Item Health Timeline</a></li>
                    </ul>
                </li>

                <!-- Rentals -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-arrow-repeat"></i> Rentals
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="checkout.php">Rental Checkout</a></li>
                        <li><a class="dropdown-item" href="active_rentals.php">Active Rentals / Return</a></li>
                    </ul>
                </li>

                <!-- Inspections -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-clipboard-check"></i> Inspections
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="inspection_templates.php">Inspection Templates</a></li>
                    </ul>
                </li>

                <!-- Damage -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-exclamation-triangle"></i> Damage
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="damage_review.php">Damage Review</a></li>
                    </ul>
                </li>

                <!-- System -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> System
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="test_db.php">DB Status</a></li>
                    </ul>
                </li>

            </ul>

            <!-- Right side -->
            <span class="navbar-text text-light small">
                PHP • MySQL • Rental Management
            </span>
        </div>
    </div>
</nav>

<div class="container mt-4">
