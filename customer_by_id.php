<?php
require "sql.php";

$customer = null;
$error = "";

if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    
    // Validation: نقطة 12 (التأكد إن الـ ID رقم)
    if (!is_numeric($id) || $id <= 0) {
        $error = "Please enter a valid numeric Customer ID.";
    } else {
        $sql = "SELECT * FROM customers WHERE id = '$id'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $customer = mysqli_fetch_assoc($result);
        } else {
            $error = "No customer found with ID: " . htmlspecialchars($id);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Customer by ID</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">MySQL Project</div>
        <div class="nav-links">
            <a href="main.php">Home</a>
            <a href="customers_salary.php">Customers > 20k</a>
            <a href="customer_by_id.php">Search ID</a>
        </div>
    </nav>

    <div class="container">
        <h2>Search Customer Details By ID</h2>
        
        <form method="GET" action="customer_by_id.php">
            <input type="text" name="id" placeholder="Enter Customer ID" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($error): ?>
            <p style="color: red; margin-top: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($customer): ?>
            <table border="1" style="margin-top: 20px; width: 100%;">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Salary</th>
                    <th>City</th>
                </tr>
                <tr>
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo $customer['name']; ?></td>
                    <td><?php echo $customer['salary']; ?></td>
                    <td><?php echo $customer['city']; ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>