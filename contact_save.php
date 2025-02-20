<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array for errors
    $errors = []; // Initialize the errors array

    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message'])); // Correct variable name

    // Validate the form data
    if (empty($name) || preg_match('/\d/', $name)) {
        $errors[] = 'Name is required and should not contain digits.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($message)) { // Use the correct variable here
        $errors[] = 'Enter Message ';
    }

    // If there are no errors, proceed to save the data
    if (empty($errors)) {
        // Database connection
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password
        $dbname = "contact"; // Replace with your database name

        // Create a new connection to MySQL
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO contact_details (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message); // Ensure 'message' is bound correctly

        // Execute the query
        if ($stmt->execute()) {
            echo 'Message saved!';
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
