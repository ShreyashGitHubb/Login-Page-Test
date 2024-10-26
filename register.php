<?php 
// Include the database connection file
include 'connect.php'; // Ensure this file establishes the connection and assigns it to $conn

if (isset($_POST['signUp'])) {
    $firstName = trim($_POST['fName']);
    $lastName = trim($_POST['lName']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Use password_hash for better security

    // Check if the email already exists using prepared statements
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email=?");
    if ($checkEmail === false) {
        die("MySQL prepare error: " . $conn->error);
    }
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        // Insert the new user using prepared statements
        $insertQuery = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        if ($insertQuery === false) {
            die("MySQL prepare error: " . $conn->error);
        }
        $insertQuery->bind_param("ssss", $firstName, $lastName, $email, $password);

        if ($insertQuery->execute() === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $insertQuery->error;
        }
    }

    // Close the prepared statements
    $checkEmail->close();
    if (isset($insertQuery)) {
        $insertQuery->close();
    }
}

if (isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Store plain password for verification

    // Use prepared statements to prevent SQL injection
    $sql = $conn->prepare("SELECT * FROM users WHERE email=?");
    if ($sql === false) {
        die("MySQL prepare error: " . $conn->error);
    }
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password using password_verify
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "Not Found, Incorrect Email or Password";
        }
    } else {
        echo "Not Found, Incorrect Email or Password";
    }

    // Close the prepared statements
    $sql->close();
}

// Close the connection only at the end
$conn->close(); // Close the connection at the end
?>
