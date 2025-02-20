<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array for errors
   

    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $department = htmlspecialchars(trim($_POST['department']));
    $date = htmlspecialchars(trim($_POST['date']));

    // Validate the form data
    if (empty($name) || preg_match('/\d/', $name)) {
        $errors[] = 'Name is required and should not contain digits.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    }

    if (empty($date)) {
        $errors[] = 'Preferred date is required.';
    }

    // If there are no errors, proceed to save the data
    if (empty($errors)) {
        // Database connection
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password
        $dbname = "appointments"; // Replace with your database name

        // Create a new connection to MySQL
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, department, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $department, $date);

        // Execute the query
        if ($stmt->execute()) {
            echo 'Appointment booked successfully!';
        } else {
            echo 'Error: ' . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
    }
} else {
    echo 'Invalid request method.';
}
?>
