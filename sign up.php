<?php
session_start();
if (isset($_SESSION["user"])){
    header("Location: index.php");
    exit();
}

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    // $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $errors = array();

    // Validate input data (you can add more validation as needed)
    if (empty($username) || empty($email) || empty($password) || empty($cpassword)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $cpassword) {
        array_push($errors, "Passwords do not match");
    }

    // Check if email already exists
    $sql = "SELECT * FROM login WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowcount = mysqli_num_rows($result);

    if ($rowcount > 0) {
        array_push($errors, "Email already exists");
    }

    // If there are no errors, insert the data into the database
    if (empty($errors)) {
        $sql = "INSERT INTO login (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert alert-success'>Successfully registered</div>";
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>REGISTRATION FORM </title>
    <link rel="stylesheet" href="style 1.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
</head>
<body>
   <div class="box">
    <form action="signup.php" method="post">
    <h2>sign up</h2>
            <div class="inputBox">
                <input type="text" name="username" placeholder="enter your username" required>
            </div>
            <div class="inputBox">
                <input type="email" name="email" placeholder="enter your email" required >
            </div>
            <div class="inputBox">
                <input type="password" name="password" placeholder="enter your password" required >
            </div>
            <div class="inputBox">
                <input type="password" name="cpassword" placeholder="confirm password" required>
                <i class="fa fa-envelope"></i>

            </div>
            <div class="submit">
                <input type="submit" name="submit" value="Register">
            </div>

            <div class="links">
        <span>Already have an account?<br> </span><a href="login.php">login here</a>
            </div>
    </form>
   </div>
</body>
</html>