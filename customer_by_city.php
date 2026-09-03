<?php
require "sql.php";

$selected_city = "";
$customers = [];

// جلب قائمة المدن بدون تكرار للـ Select
$cities_result = mysqli_query($connect, "SELECT DISTINCT city FROM customers");

if (isset($_GET['city'])) {
    $selected_city = trim($_GET['city']);
    $safe_city = mysqli_real_escape_string($connect, $selected_city);

    // Query نقطة (7): عرض العملاء في المدينة المحددة مرتبين بالأسماء
    $sql = "SELECT * FROM customers WHERE city = '$safe_city' ORDER BY name ASC";
    $result = mysqli_query($connect, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $customers[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers by City</title>
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
            <a href="customer_orders.php">Orders Count</a>
            <a href="top_products.php">Top Products</a>
            <a href="employees.php">Employees</a>
        </div>
    </nav>

    <div class="container">
        <h2>Select City to Show Customers (Sorted by Name)</h2>

        <form method="GET" action="customer_by_city.php">
            <select name="city" required>
                <option value="">-- Choose City --</option>
                <?php while ($c = mysqli_fetch_assoc($cities_result)): ?>
                    <option value="<?php echo htmlspecialchars($c['city']); ?>" 
                        <?php if ($selected_city == $c['city']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['city']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Submit</button>
        </form>

        <?php if ($selected_city): ?>
            <h3>Customers in "<?php echo htmlspecialchars($selected_city); ?>"</h3>
            <?php if (!empty($customers)): ?>
                <table border="1" style="margin-top: 15px; width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>City</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $cust): ?>
                            <tr>
                                <td><?php echo $cust['id']; ?></td>
                                <td><?php echo htmlspecialchars($cust['name']); ?></td>
                                <td><?php echo $cust['salary']; ?></td>
                                <td><?php echo htmlspecialchars($cust['city']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No customers found in this city.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>