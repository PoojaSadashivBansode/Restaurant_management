<?php
$servername = "localhost:3307"; // Change if your MySQL uses a different port
$username = "root";
$password = "";
$dbname = "soul_palace";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];
    $num_rooms = $_POST["num-rooms"];
    $total_amount = filter_var($_POST["total-amount"], FILTER_SANITIZE_NUMBER_INT); // Remove ₹ symbol

    // Convert room price to room type
    $room_types = [
        "3000" => "Single Room",
        "4500" => "Double Room",
        "7500" => "Suite"
    ];
    $room_price = $_POST["room"];
    $room_type = isset($room_types[$room_price]) ? $room_types[$room_price] : "Unknown";

    // Insert data into database
    $sql = "INSERT INTO room_bookings (name, email, phone, checkin_date, checkout_date, room_type, num_rooms, total_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $name, $email, $phone, $checkin, $checkout, $room_type, $num_rooms, $total_amount);

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
                <h2>✅ Room Booking Confirmed!</h2>
                <p>We will contact you soon with further details.</p>
                <a href='room.html' class='back-button'>Go Back</a>
            </div>
        </body>
        </html>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }


    $stmt->close();
}

$conn->close();
?>
