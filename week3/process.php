<?php 
if ($_SERVER['REQUEST_METHOD'] == "POST") { 
    $name = htmlspecialchars($_POST['name']); 
    $email = htmlspecialchars($_POST['email']); 
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) { 
        if ($password !== $confirm_password) {
            echo "<h2>Error: Passwords do not match.</h2>"; 
        } else {
            echo "<h2>You have sumitted Successfully!</h2>"; 
            echo "<p><strong>Name:</strong> $name</p>"; 
            echo "<p><strong>Email:</strong> $email</p>";
        }
    } else {  
        echo "<h2>Error: All fields are required.</h2>"; 
    } 
} else { 
    echo "<h2>Invalid Request</h2>"; 
} 
?>
