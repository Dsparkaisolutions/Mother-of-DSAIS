<?php
// Database connection
$conn = new mysqli('localhost', 'u768539030_dataspark', 'Praveen@9866', 'u768539030_dspark');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get existing details
function getExistingDetails($email, $mobile, $conn) {
    $sql = "SELECT * FROM application WHERE email = ? OR mobile = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $mobile);
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $dob = $_POST['dob'];
    $message = $_POST['message'];
    $resume = $_FILES['resume']['name'];
    $link = $_POST['link'];

    // Validate and sanitize inputs
    $name = htmlspecialchars(strip_tags($name));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars(strip_tags($mobile));
    $dob = htmlspecialchars(strip_tags($dob));
    $message = htmlspecialchars(strip_tags($message));
    $link = htmlspecialchars(strip_tags($link));

    // Check if user already exists
    $existingDetails = getExistingDetails($email, $mobile, $conn);

    if ($existingDetails) {
        // Show popup with existing details and redirect
        echo "<script>
                alert('Details already exist');
                window.location.href = 'https://dsparkai.com/career.html';
              </script>";
    } else {
        // Store details in the database
        $sql = "INSERT INTO application (name, email, mobile, dob, message, resume, link) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $email, $mobile, $dob, $message, $resume, $link);

        if ($stmt->execute()) {
            // Move uploaded resume to the designated folder
            if (move_uploaded_file($_FILES['resume']['tmp_name'], "uploads/" . basename($resume))) {
                // Call Python script to send an email
                $command = escapeshellcmd("python3 /home/u768539030/domains/dsparkai.com/public_html/send1_email.py'$name' '$email' '$mobile' '$dob' '$message' '$resume' '$link'");
                shell_exec($command);

                // Show popup for successful registration and redirect
                echo "<script>
                        alert('Registration successful');
                        window.location.href = 'https://dsparkai.com/index.html';
                      </script>";
            } else {
                echo "<script>
                        alert('ERROR IS UPLOADING FILE');
                        window.location.href = 'https://dsparkai.com/index.html';
                      </script>";
            }
                } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
