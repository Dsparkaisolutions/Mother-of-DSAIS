<?php
    // Fetch form data
    $yourname = $_POST['yourname'] ?? '';
    $youremail = $_POST['youremail'] ?? '';
    $mobilenumber = $_POST['mobilenumber'] ?? '';
    $message = $_POST['message'] ?? '';

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'datasparkaisolutions');
    if ($conn->connect_error) {
        die("Connection Failed : " . $conn->connect_error);
    }

    // Check if details already exist in the database
    $checkQuery = $conn->prepare("SELECT * FROM contact WHERE youremail = ? OR mobilenumber = ?");
    $checkQuery->bind_param("ss", $youremail, $mobilenumber); // Both should be strings (use "ss")
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // Details already exist
        echo "<script>alert('Details are already stored.');</script>";
        echo "<script>window.location.href = 'http://localhost/Mother-of-DSAIS/index.html';</script>";
    } else {
        // Prepare SQL statement to insert data into 'contact' table
        $stmt = $conn->prepare("INSERT INTO contact(yourname, youremail, mobilenumber, message) VALUES (?, ?, ?, ?)");

        // Check if prepare() succeeded
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters and execute
        $stmt->bind_param("ssss", $yourname, $youremail, $mobilenumber, $message); // All should be strings (use "ssss")
        $execval = $stmt->execute();

        // Check execution result
        if ($execval === false) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Proceed with sending email if database insertion was successful
        if ($execval) {
            // Call Python script to send email with arguments
            $command = "python send_email.py \"$yourname\" \"$youremail\" \"$mobilenumber\" \"$message\"";
            $output = shell_exec($command); 
            // Assuming send_email.py handles sending and returns relevant output
            
            // Provide feedback to the user
            echo "<script>alert('Registered successfully.');</script>";
            
            // Redirect after successful submission
            echo "<script>window.location.href = 'http://localhost/Mother-of-DSAIS/index.html';</script>";
        } else {
            // Registration failed
            echo "<script>alert('Error: Failed to register.');</script>";
        }
    }

    // Close check query statement
    $checkQuery->close();
?>


