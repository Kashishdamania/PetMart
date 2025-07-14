<?php
session_start();
include 'navbar.php';
$conn = new mysqli("localhost", "root", "", "petmart");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Query to get the user data by email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc(); // Fetch the user data
        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Logged in successfully — store username in session
            $_SESSION["username"] = $user['username'];
            $_SESSION["uid"] = $user['uid']; 
            header("Location: index.php"); // Redirect after login
            exit();
        } else {
            echo "<script>alert('Invalid email or password');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | PetMart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #F7FDFD;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #2F4F4F;
        }

        .card form {
            display: flex;
            flex-direction: column;
        }

        .card input {
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .card button {
            padding: 12px;
            background-color: #C9E4DE;
            color: #333;
            border: 2px solid #A4CBB2;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .card button:hover {
            background-color: #a8d3c6;
        }

        .card p {
            margin-top: 15px;
            font-size: 14px;
        }

        .card a {
            color: #66a593;
            text-decoration: none;
        }

        .card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Welcome Back to PetMart 🐾</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
