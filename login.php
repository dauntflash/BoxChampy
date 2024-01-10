<?php
session_start();

// Redirect to index.php if user is already logged in
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once "connection.php";
require_once "header.php";

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

<link rel="stylesheet" href="asserts/css/form.css">

   <div class="box">
    <form action="login.php" method="post">
    <h2>login</h2>
            <div class="inputBox">
                <label>email</label>
                <input type="email" name="email" placeholder="enter your email" required>
            </div>
            <div class="inputBox">
                <label>password</label>
                <input type="password" name="password" placeholder="enter your password" required>
            </div>
            <div class="submit">
                <input type="submit" name="login" value="login">
            </div>

            
            <div class="links">
        <span>Don't have an account?<br> </span><a href="signup.php">sign up here</a>
            </div>
    </form>
   </div>
</body>
</html>