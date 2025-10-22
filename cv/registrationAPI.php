<?php 
include "./db.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        // Insert into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO system_user (name, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $hashed_password);

        if ($stmt->execute()) {
            echo json_encode([
                "toastMsg" => "Account created successfully!",  
                "toastType" => "success"
            ]);
        } else {
            echo json_encode([
                "toastMsg" => "Error creating account: " . $stmt->error,  
                "toastType" => "danger"
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            "toastMsg" => "Passwords do not match!",  
            "toastType" => "danger"
        ]);
    }
}
?>