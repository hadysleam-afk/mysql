<?php
require "sql.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Selling Products</title>
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
        </div>
    </nav>

    <div class="container">
        <h2>Top Selling Products & Total Earnings</h2>

        <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity Sold</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query نقطة (4): حساب إجمالي الكميات المباعة والربح وإظهار الأكثر مبيعاً الأول
                $sql = "SELECT 
                            products.name, 
                            SUM(order_details.quantity) AS total_quantity, 
                            SUM(order_details.quantity * order_details.price) AS total_revenue
                        FROM order_details
                        JOIN products ON order_details.product_id = products.id
                        GROUP BY products.id, products.name
                        ORDER BY total_quantity DESC";

                $result = mysqli_query($connect, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_revenue']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No product sales found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>