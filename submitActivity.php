<?php
$servername = "localhost:3307";  // Update the port if different
$username = "root";
$password = "";
$dbname = "soul_palace";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = $_POST["name"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $activity = $_POST["activity"];
    $time_slot = $_POST["time"];
    $persons = $_POST["persons"];

    // Remove ₹ symbol and commas, then convert to integer
    $price_per_person  = isset($_POST["price"]) ? (int)str_replace(["₹", ","], "", $_POST["price"]) : 0;
    $total_price = isset($_POST["total-price"]) ? (int)str_replace(["₹", ","], "", $_POST["total-price"]) : 0;

    // Insert data into MySQL
    $sql = "INSERT INTO activity_bookings (name, contact, email, activity, time_slot, price_per_person, persons, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiii", $name, $contact, $email, $activity, $time_slot, $price_per_person, $persons, $total_price);

    if ($stmt->execute()) {
        // ✅ BIG, CENTERED SUCCESS MESSAGE
        echo "
        <html>
        <head>
            <title>Booking Confirmation</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap' rel='stylesheet'>
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    text-align: center;
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .confirmation-container {
                    background-color: #fff;
                    padding: 50px;
                    border-radius: 15px;
                    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
                    max-width: 600px;
                    width: 90%;
                }
                h2 {
                    font-size: 32px;
                    color: green;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 20px;
                    color: #333;
                    margin-bottom: 30px;
                }
                .back-button {
                    padding: 12px 25px;
                    background-color: #007BFF;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-size: 18px;
                    border-radius: 8px;
                    text-decoration: none;
                    display: inline-block;
                }
                .back-button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class='confirmation-container'>
                <h2>✅ Activity Booking Confirmed!</h2>
                <p>We will contact you soon with further details.</p>
                <a href='room.html' class='back-button'>Go Back</a>
            </div>
        </body>
        </html>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }




    // Close statement and connection
    $stmt->close();
}
$conn->close();
?>
