<?php
require "sql.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders Count</title>
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
        </div>
    </nav>

    <div class="container">
        <h2>Total Orders Per Customer</h2>

        <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Total Orders</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query نقطة (3): نجيب اسم العميل وعدد الأوردرات بتاعته
                $sql = "SELECT customers.name, COUNT(orders.id) AS total_orders 
                        FROM customers 
                        LEFT JOIN orders ON customers.id = orders.customer_id 
                        GROUP BY customers.id, customers.name";

                $result = mysqli_query($connect, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_orders']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>