<?php
// Database configuration
$servername = "localhost"; // Change this to your database server
$username = "root";        // Change this to your database username
$password = "";            // Change this to your database password
$dbname = "event_management"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $event = htmlspecialchars($_POST['event']);
    $date = htmlspecialchars($_POST['date']);
    $guests = htmlspecialchars($_POST['guests']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, event_type, event_date, guests) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $name, $email, $phone, $event, $date, $guests);

    // Execute the statement
    if ($stmt->execute()) {
        // Display confirmation message
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Booking Confirmation</title>
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
        </head>
        <body>
            <div class='container mt-5'>
                <h2>Booking Confirmation</h2>
                <p>Thank you, <strong>$name</strong>, for booking your event with us!</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Event Type:</strong> $event</p>
                <p><strong>Event Date:</strong> $date</p>
                <p><strong>Number of Guests:</strong> $guests</p>
                <a href='booking_form.php' class='btn btn-primary'>Book Another Event</a>
            </div>
        </body>
        </html>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to booking form if accessed directly
    header("Location: booking_form.php");
    exit();
}
?>