<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

if ($user && $password === $user["password"]) {
        $_SESSION["user"] = true;
        $_SESSION["email"] = $email;
        $_SESSION["username"] = $user["username"];
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>REGISTRATION FORM </title>
    <link rel="stylesheet" href="style 1.css">
</head>
<body>
   <div class="box">
    <form action="login.php" method="post">
    <h2>login</h2>
            <div class="inputBox">
                <input type="email" name="email" required>
                <p>email</p>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required>
                <p>password</p>
            </div>
            <div class="submit">
                <input type="submit" name="login" value="login">
            </div>
            <div class="option">
                <a href="#">continue with google</a>
            </div>
            <div class="links">
        <span>Don't have an account?<br> </span><a href="signup.php">sign up here</a>
            </div>
    </form>
   </div>
</body>
</html>