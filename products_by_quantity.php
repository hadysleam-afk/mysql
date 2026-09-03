<?php
require "sql.php";

$products = [];
$error = "";
$min_qty = "";

if (isset($_GET['qty'])) {
    $min_qty = trim($_GET['qty']);

    // Validation (نقطة 12): التأكد من إن الرقم بين 100 و 5000 ورقم صحيح
    if (!is_numeric($min_qty) || $min_qty < 100 || $min_qty > 5000) {
        $error = "Please enter a valid number between 100 and 5000.";
    } else {
        $safe_qty = (int)$min_qty;

        // Query نقطة (8): تجميع المنتجات التي إجمالي قطعها المباعة أكبر من الرقم المدخل
        $sql = "SELECT 
                    products.name, 
                    SUM(order_details.quantity) AS total_quantity
                FROM order_details
                JOIN products ON order_details.product_id = products.id
                GROUP BY products.id, products.name
                HAVING total_quantity > $safe_qty";

        $result = mysqli_query($connect, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        } else {
            $error = "No products found with total sold quantity greater than " . $safe_qty;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products by Quantity Filter</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">MySQL Project</div>
        <div class="nav-links">
            <a href="main.php">Home</a>
            <a href="customers_salary.php">Customers > 20k</a>
            <a href="customer_by_id.php">Search ID</a>
            <a href="search_by_name.php">Search Name</a>
            <a href="customer_by_city.php">Customers by City</a>
            <a href="products_by_quantity.php">Filter Quantity (100-5000)</a>
            <a href="customer_orders.php">Orders Count</a>
            <a href="top_products.php">Top Products</a>
            <a href="employees.php">Employees</a>
        </div>
    </nav>

    <div class="container">
        <h2>Find Products with Total Sold Quantity Greater Than Value</h2>

        <form method="GET" action="products_by_quantity.php">
            <input type="number" min="100" max="5000" name="qty" placeholder="Enter number (100 - 5000)" value="<?php echo htmlspecialchars($min_qty); ?>" required>
            <button type="submit">Filter</button>
        </form>

        <?php if ($error): ?>
            <p style="color: red; margin-top: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($products)): ?>
            <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Total Quantity Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo $p['total_quantity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>