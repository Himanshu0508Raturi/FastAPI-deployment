<?php
// Database Connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "ai-database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Encrypt password

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM signup WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO signup (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $success = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h3 class="text-center">Register</h3>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
