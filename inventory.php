<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold text-center">
                    <i class="bi bi-boxes"></i> Inventory List
                </div>

                <div class="card-body">

                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Deposit (₹)</th>
                                <th>Daily Rate (₹)</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $sql = "SELECT * FROM items";
                        $result = $conn->query($sql);

                        if ($result->num_rows == 0) {
                            echo "
                            <tr>
                                <td colspan='7' class='text-center text-muted'>
                                    No items found in inventory
                                </td>
                            </tr>";
                        }

                        while ($row = $result->fetch_assoc()) {

                            $statusClass = match ($row['status']) {
                                'available' => 'success',
                                'rented' => 'warning',
                                'maintenance' => 'danger',
                                default => 'secondary'
                            };

                            echo "<tr>";
                            echo "<td>{$row['item_code']}</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['category']}</td>";
                            echo "<td>{$row['deposit_amount']}</td>";
                            echo "<td>{$row['daily_rate']}</td>";

                            echo "
                                <td>
                                    <span class='badge bg-$statusClass'>
                                        {$row['status']}
                                    </span>
                                </td>";

                            if ($row['status'] == 'maintenance') {
                                echo "
                                <td class='text-center'>
                                    <a href='item_status.php?id={$row['item_id']}&status=available'
                                       class='btn btn-sm btn-success'>
                                        <i class='bi bi-check-circle'></i> Mark Available
                                    </a>
                                </td>";
                            } else {
                                echo "
                                <td class='text-center'>
                                    <a href='item_status.php?id={$row['item_id']}&status=maintenance'
                                       class='btn btn-sm btn-danger'>
                                        <i class='bi bi-tools'></i> Mark Maintenance
                                    </a>
                                </td>";
                            }

                            echo "</tr>";
                        }
                        ?>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
