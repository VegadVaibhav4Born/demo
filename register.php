<?php
include 'config.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = mysqli_query($con, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
    if ($query) {
        header('Location: login.php');
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
