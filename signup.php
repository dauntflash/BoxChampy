<?php
session_start();

// Redirect if user is already logged in
if (isset($_SESSION["user"])) {
    header("Location: index.php?message=AlreadyLoggedIn");
    exit();
}

require_once "connection.php";
require_once "header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["cpassword"];

    $errors = array();

    // Validate input data
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $confirmPassword) {
        array_push($errors, "Passwords do not match");
    }

    // Check if email already exists
    if (emailExists($email)) {
        array_push($errors, "Email already exists");
    }

    // If there are no errors, insert the data into the database
    if (empty($errors)) {
        insertUser($username, $email, $password);
        echo "<div class='alert alert-success'>Successfully registered</div>";
        header("Location: index.php");
        exit();

    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}

function emailExists($email) {
    global $conn;
    $sql = "SELECT * FROM login WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowCount = mysqli_num_rows($result);
    return $rowCount > 0;
}

function insertUser($username, $email, $password) {
    global $conn;
    $sql = "INSERT INTO login (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
    mysqli_stmt_execute($stmt);
}
?>

<link rel="stylesheet" href="asserts/css/form.css">


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