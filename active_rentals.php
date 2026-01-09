<?php include "partials/header.php"; ?>
<?php include "config/db.php"; ?>

<?php
// STEP 1: Auto-mark overdue rentals
$conn->query("
    UPDATE rentals
    SET rental_status = 'overdue'
    WHERE rental_status = 'active'
    AND end_date < CURDATE()
");
?>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold text-center">
                    <i class="bi bi-arrow-repeat"></i> Active & Overdue Rentals
                </div>

                <div class="card-body">

                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Rental ID</th>
                                <th>Customer</th>
                                <th>Item</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $sql = "
                        SELECT 
                            r.rental_id,
                            r.start_date,
                            r.end_date,
                            r.rental_status,
                            c.name AS customer_name,
                            i.name AS item_name
                        FROM rentals r
                        JOIN customers c ON r.customer_id = c.customer_id
                        JOIN items i ON r.item_id = i.item_id
                        WHERE r.rental_status IN ('active', 'overdue')
                        ORDER BY r.end_date ASC
                        ";

                        $result = $conn->query($sql);

                        if ($result->num_rows == 0) {
                            echo "
                            <tr>
                                <td colspan='7' class='text-center text-muted'>
                                    No active or overdue rentals found
                                </td>
                            </tr>";
                        }

                        while ($row = $result->fetch_assoc()) {

                            // Status badge
                            if ($row['rental_status'] == 'overdue') {
                                $statusBadge = "<span class='badge bg-danger'>Overdue</span>";
                            } else {
                                $statusBadge = "<span class='badge bg-success'>Active</span>";
                            }

                            echo "<tr>";
                            echo "<td>{$row['rental_id']}</td>";
                            echo "<td>{$row['customer_name']}</td>";
                            echo "<td>{$row['item_name']}</td>";
                            echo "<td>{$row['start_date']}</td>";
                            echo "<td>{$row['end_date']}</td>";
                            echo "<td>$statusBadge</td>";
                            echo "
                                <td class='text-center'>
                                    <a href='return.php?rental_id={$row['rental_id']}'
                                       class='btn btn-sm btn-danger'>
                                        <i class='bi bi-arrow-return-left'></i> Return
                                    </a>
                                </td>";
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
