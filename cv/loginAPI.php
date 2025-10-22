<?php 
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['toastMsg'] = "Please enter both username and password.";
        $_SESSION['toastType'] = "danger";
        header("Location: index.php");
        exit();
    }

    // Fetch user by username
    $stmt = $conn->prepare("SELECT id, password FROM system_user WHERE username = ?");
    if (!$stmt) {
        $_SESSION['toastMsg'] = "Database error: " . $conn->error;
        $_SESSION['toastType'] = "danger";
        header("Location: index.php");
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Store user data in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            
            // Clear any previous error messages
            unset($_SESSION['toastMsg'], $_SESSION['toastType']);
            
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['toastMsg'] = "Invalid username or password.";
            $_SESSION['toastType'] = "danger";
        }
    } else {
        $_SESSION['toastMsg'] = "Invalid username or password.";
        $_SESSION['toastType'] = "danger";
    }
    
    $stmt->close();
    $conn->close();
    
    header("Location: index.php");
    exit();
} else {
    // If not POST request, redirect to index
    header("Location: index.php");
    exit();
}
?>