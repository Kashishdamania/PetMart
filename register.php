<?php
ob_start();
session_start();
include 'navbar.php';
$mysqli = new mysqli("localhost", "root", "", "petmart");
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $username = $mysqli->real_escape_string(trim($_POST["username"]));
    $email = $mysqli->real_escape_string(trim($_POST["email"]));
   // $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);  // Password Hashing
   $raw_password = trim($_POST["password"]);

    // Password strength validation: min 8 chars, 1 uppercase, 1 lowercase, 1 digit, 1 special char
   if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $raw_password)) {
    $error = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    } else {
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
   }
 
    $mobile = $mysqli->real_escape_string(trim($_POST["mobile"]));

    // Email Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } 
    // Mobile Number Validation
    elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $error = "Invalid mobile number! It should be a 10 digit number.";
    }
    else {
        // Check if email is already registered
        $check = $mysqli->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Insert new user into database
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password, mobile) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $mobile);
            if ($stmt->execute()) {
                $_SESSION["email"] = $email;
                $_SESSION["uid"] = $stmt->insert_id;
                $_SESSION["username"] = $username;
                header("Location: index.php"); // Redirect to the home page after successful registration
                exit();
            } else {
                $error = "Registration failed! Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | PetMart</title>
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
    <h2>Join the PetMart Family 🐶🐱</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required
       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"
       title="Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.">
       <input type="text" name="mobile" placeholder="Mobile Number" pattern="[0-9]{10}" title="Enter 10 digit number" required>
        <button type="submit">Register</button>
    </form>
    <p>Already a user? <a href="login.php">Login</a></p>
</div>

</body>
</html>
<?php ob_end_flush(); ?>
