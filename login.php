<?php
session_start();
require "sql.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    // Validation (نقطة 12)
    if (empty($name) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif (strlen($name) > 50 || strlen($password) > 50) {
        $error = "Input length exceeds allowed maximum (50 chars).";
    } else {
        $safe_name = mysqli_real_escape_string($connect, $name);
        $safe_password = mysqli_real_escape_string($connect, $password);

        $sql = "SELECT * FROM customers WHERE name = '$safe_name' AND password = '$safe_password'";
        $result = mysqli_query($connect, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            header("Location: main.php");
            exit();
        } else {
            $error = "Invalid Customer Name or Password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" style="margin-top: 50px; width: 400px;">
        <h2>Login System</h2>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="name" placeholder="Customer Name (e.g. Ahmed Ali)" style="width: 100%; margin-bottom: 10px;" required>
            <input type="password" name="password" placeholder="Password (default: 123456)" style="width: 100%; margin-bottom: 10px;" required>
            <button type="submit" style="width: 100%;">Login</button>
        </form>
    </div>
</body>
</html>