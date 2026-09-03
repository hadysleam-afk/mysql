<?php
require "sql.php";

$customers = [];
$error = "";

if (isset($_GET['name'])) {
    $name = trim($_GET['name']);

    // Validation: نقطة 12 (التحقق من المدخلات ومراعاة ألا تتعدى طول معقول)
    if (empty($name)) {
        $error = "Please enter a name to search.";
    } elseif (strlen($name) > 50) {
        $error = "Search name is too long.";
    } else {
        $safe_name = mysqli_real_escape_string($connect, $name);
        $sql = "SELECT * FROM customers WHERE name LIKE '%$safe_name%'";
        $result = mysqli_query($connect, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $customers[] = $row;
            }
        } else {
            $error = "No customers found matching: " . htmlspecialchars($name);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Customer by Name</title>
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
            <a href="customer_orders.php">Orders Count</a>
            <a href="top_products.php">Top Products</a>
            <a href="employees.php">Employees</a>
        </div>
    </nav>

    <div class="container">
        <h2>Search Customers By Name</h2>

        <form method="GET" action="search_by_name.php">
            <input type="text" name="name" placeholder="Enter customer name..." required>
            <button type="submit">Search</button>
        </form>

        <?php if ($error): ?>
            <p style="color: red; margin-top: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($customers)): ?>
            <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><?php echo $c['id']; ?></td>
                            <td><?php echo htmlspecialchars($c['name']); ?></td>
                            <td><?php echo $c['salary']; ?></td>
                            <td><?php echo htmlspecialchars($c['city']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>