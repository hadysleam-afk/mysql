<?php
require "sql.php";

$product_id = "";
$product_info = null;
$error = "";

if (isset($_GET['product_id'])) {
    $product_id = trim($_GET['product_id']);

    // Validation (نقطة 12)
    if (!is_numeric($product_id) || $product_id <= 0) {
        $error = "Please enter a valid numeric Product ID.";
    } else {
        $safe_id = (int)$product_id;

        // Query نقطة (10)
        $sql = "SELECT 
                    p.name AS product_name,
                    COUNT(od.id) AS times_sold,
                    GROUP_CONCAT(DISTINCT od.order_id ORDER BY od.order_id ASC SEPARATOR ', ') AS order_ids,
                    GROUP_CONCAT(DISTINCT c.name ORDER BY c.salary DESC SEPARATOR ', ') AS buyer_names
                FROM products p
                LEFT JOIN order_details od ON p.id = od.product_id
                LEFT JOIN orders o ON od.order_id = o.id
                LEFT JOIN customers c ON o.customer_id = c.id
                WHERE p.id = $safe_id
                GROUP BY p.id, p.name";

        $result = mysqli_query($connect, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $product_info = mysqli_fetch_assoc($result);
        } else {
            $error = "No product found with ID: " . $safe_id;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">MySQL Project</div>
        <div class="nav-links">
            <a href="main.php">Home</a>
            <a href="top_rich_by_city.php">Top 3 Rich</a>
            <a href="product_details.php">Product Details</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <div class="container">
        <h2>Product Selling Details By Product ID</h2>

        <form method="GET" action="product_details.php">
            <input type="number" name="product_id" placeholder="Enter Product ID (e.g. 1)" value="<?php echo htmlspecialchars($product_id); ?>" required>
            <button type="submit">Get Details</button>
        </form>

        <?php if ($error): ?>
            <p style="color: red; margin-top: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($product_info): ?>
            <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
                <tr>
                    <th>Product Name</th>
                    <td><?php echo htmlspecialchars($product_info['product_name']); ?></td>
                </tr>
                <tr>
                    <th>Times Sold (عدد مرات البيع)</th>
                    <td><?php echo $product_info['times_sold']; ?></td>
                </tr>
                <tr>
                    <th>Order IDs (أرقام الأوردرات)</th>
                    <td><?php echo $product_info['order_ids'] ? $product_info['order_ids'] : 'None'; ?></td>
                </tr>
                <tr>
                    <th>Buyers (الأغنى للأفقر)</th>
                    <td><?php echo $product_info['buyer_names'] ? $product_info['buyer_names'] : 'None'; ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>