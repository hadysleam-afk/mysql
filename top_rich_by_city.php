<?php
require "sql.php";

$city = "";
$rich_customers = [];
$error = "";

if (isset($_GET['city'])) {
    $city = trim($_GET['city']);
    
    // Validation: نقطة 12 (التحقق من عدم الفراغ وطول النص)
    if (empty($city)) {
        $error = "Please enter a city name.";
    } elseif (strlen($city) > 50) {
        $error = "City name is too long.";
    } else {
        $safe_city = mysqli_real_escape_string($connect, $city);
        
        // Query نقطة (9): أغنى 3 أشخاص في المدينة
        $sql = "SELECT name, salary, city 
                FROM customers 
                WHERE city LIKE '%$safe_city%' 
                ORDER BY salary DESC 
                LIMIT 3";
                
        $result = mysqli_query($connect, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rich_customers[] = $row;
            }
        } else {
            $error = "No customers found in city: " . htmlspecialchars($city);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 3 Richest by City</title>
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
        <h2>Top 3 Richest Customers in City</h2>

        <form method="GET" action="top_rich_by_city.php">
            <input type="text" name="city" placeholder="Enter City Name (e.g. Cairo)" value="<?php echo htmlspecialchars($city); ?>" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($error): ?>
            <p style="color: red; margin-top: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($rich_customers)): ?>
            <table border="1" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rich_customers as $cust): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cust['name']); ?></td>
                            <td><?php echo number_format($cust['salary'], 2); ?></td>
                            <td><?php echo htmlspecialchars($cust['city']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>