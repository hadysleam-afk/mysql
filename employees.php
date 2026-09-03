<?php
require "sql.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees & Managers</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">MySQL Project</div>
        <div class="nav-links">
            <a href="main.php">Home</a>
            <a href="customers_salary.php">Customers > 20k</a>
            <a href="customer_by_id.php">Search ID</a>
            <a href="customer_orders.php">Orders Count</a>
            <a href="top_products.php">Top Products</a>
            <a href="employees.php">Employees</a>
        </div>
    </nav>

    <div class="container">
        <h2>Employees List with Their Managers (Self-Join)</h2>

        <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Manager Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query نقطة (5): Self-Join للجدول مع نفسه لعرض الموظف والمدير
                $sql = "SELECT 
                            e.name AS employee_name, 
                            IFNULL(m.name, 'No Manager (Top Boss)') AS manager_name
                        FROM employees e
                        LEFT JOIN employees m ON e.manager_id = m.id";

                $result = mysqli_query($connect, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['manager_name']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No employees found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>