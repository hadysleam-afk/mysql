<?php
require "sql.php";
$sql = "SELECT * FROM customers WHERE salary > 20000";
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers with High Salary</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">

        <div class="logo">
            MySQL Project
        </div>

        <div class="nav-links">
            <a href="main.php">Home</a>
            <a href="customers_salary.php">Customers</a>
        </div>

    </nav>

    <div class="container">

        <h1>Customers With Salary More Than 20,000</h1>

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Salary</th>
            </tr>

            <?php

            while ($customer = mysqli_fetch_assoc($result)) {

            ?>

                <tr>

                    <td>
                        <?php echo $customer['id']; ?>
                    </td>

                    <td>
                        <?php echo $customer['name']; ?>
                    </td>

                    <td>
                        <?php echo $customer['salary']; ?>
                    </td>

                </tr>

            <?php

            }

            ?>

        </table>

    </div>    
</body>
</html>