<?php
session_start();
require_once './db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("location: student_login.php");
    exit();
}

// Fetch student details from the database
$student_id = $_SESSION['student_id'];
$sql = "SELECT * FROM student WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) == 1) {
    $student = mysqli_fetch_assoc($result);
} else {
    // Redirect to login if user not found
    header("location: student_login.php");
    exit();
}

// Check if student has registered for a bus
$sql_check_registration = "SELECT * FROM bus_registrations WHERE user_id_student = {$student['id']}";
$result_check_registration = mysqli_query($conn, $sql_check_registration);

$registered = false;
if ($result_check_registration && mysqli_num_rows($result_check_registration) > 0) {
    $registered = true;
    $registration_details = mysqli_fetch_assoc($result_check_registration);

    // Fetch location data of the driver for the booked bus
    $bus_id = $registration_details['bus_id'];
    $sql_driver_id = "SELECT driver_id FROM buses WHERE id = $bus_id";
    $result_driver_id = mysqli_query($conn, $sql_driver_id);
    
    if ($result_driver_id && mysqli_num_rows($result_driver_id) == 1) {
        $driver_id_row = mysqli_fetch_assoc($result_driver_id);
        $driver_id = $driver_id_row['driver_id'];
        
        $sql_location_data = "SELECT * FROM LocationData WHERE driver_id = $driver_id ORDER BY timestamp DESC LIMIT 1";
        $result_location_data = mysqli_query($conn, $sql_location_data);
        
        if ($result_location_data && mysqli_num_rows($result_location_data) > 0) {
            $location_data = mysqli_fetch_assoc($result_location_data);
        }
    }
}

// Handle bus registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bus_id'])) {
    $bus_id = $_POST['bus_id'];
    $registration_date = date('Y-m-d'); // Assuming registration date is today's date

    // Insert registration into the database
    $sql_insert_registration = "INSERT INTO bus_registrations (user_id_student, bus_id, registration_date) VALUES ({$student['id']}, $bus_id, '$registration_date')";
    if (mysqli_query($conn, $sql_insert_registration)) {
        // Update available seats for the registered bus
        $sql_update_seats = "UPDATE buses SET capacity = capacity - 1 WHERE id = $bus_id";
        mysqli_query($conn, $sql_update_seats);

        // Registration successful, reload page to display registration details
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="./css/student_dashboard.css">
</head>
<body>
 
<header class="header-outer">
	<div class="header-inner responsive-wrapper">
		<div class="header-logo">
        <h2>Welcome, <?php echo $student['student_id']; ?></h2>
		</div>
		<nav class="header-navigation">
			<a href="#">Home</a>
			<a href="staff_dashboard.php">Dashboard</a>
			<a href="./Homepage/index.html">Log Out</a>
			<button>Menu</button>
		</nav>
	</div>
</header>
<main class="main">
	<div class="main-content responsive-wrapper">
		<article class="widget">
        
        <h3>Student Information : </h3>
        <p style="font-weight : bold;">Email : <?php echo $student['email']; ?></p>
        <p style="font-weight : bold;">Username: <?php echo $student['student_id']; ?></p>
        <br>
        <?php if ($registered) { ?>
            <h3>Bus Registration Details : </h3>
            <p>You are registered for Bus  <?php echo $registration_details['bus_id']; ?>.</p>
            <p>Registration Date : <?php echo $registration_details['registration_date']; ?></p>
            <br>
            <?php if (isset($location_data)) { ?>
                <h3>Driver Location :</h3>
                <p>Latitude: <?php echo $location_data['latitude']; ?></p>
                <p>Longitude: <?php echo $location_data['longitude']; ?></p>
                <p>Accuracy: <?php echo $location_data['accuracy']; ?></p>
                <p>Timestamp: <?php echo $location_data['timestamp']; ?></p>
                <form method="get" action="https://www.google.com/maps/search/">
                    <input type="hidden" name="api" value="1">
                    <input type="hidden" name="query" value="<?php echo $location_data['latitude']; ?>,<?php echo $location_data['longitude']; ?>">
                    <br>
                    <button>
  <span>See Your Bus Location</span>
  <svg viewBox="-5 -5 110 110" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0,0 C0,0 100,0 100,0 C100,0 100,100 100,100 C100,100 0,100 0,100 C0,100 0,0 0,0"/>
  </svg>
</button>
                </form>
            <?php } else { ?>
                <p>Location data not available.</p>
            <?php } ?>
        <?php } else { ?>
            <h3>Bus Registration</h3>
            <p>You are not registered for any bus.</p>
            <p>Please select a bus for registration:</p>
            <form method="post" action="student_dashboard.php">
                <select name="bus_id" style="padding: 10px; font-size: 16px; border-radius: 5px;">
                    <?php
                    // Fetch available buses from the database
                    $sql_fetch_buses = "SELECT * FROM buses";
                    $result_fetch_buses = mysqli_query($conn, $sql_fetch_buses);
                    if ($result_fetch_buses && mysqli_num_rows($result_fetch_buses) > 0) {
                        while ($bus = mysqli_fetch_assoc($result_fetch_buses)) {
                        
                            echo "<option value='{$bus['id']}'  style='padding: 10px 16px ; padding-bottom:15px; font-size: 16px; border-radius: 10px; transition: background-color 0.8s ease;'> 
                            {$bus['bus_number']} - {$bus['destination']} (Available Seats: {$bus['capacity']})</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No buses available</option>";
                    }
                    ?>
                </select><br><br>



                <button>
  <span>Register</span>
  <svg viewBox="-5 -5 110 110" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0,0 C0,0 100,0 100,0 C100,0 100,100 100,100 C100,100 0,100 0,100 C0,100 0,0 0,0"/>
  </svg>
</button>
            </form>
            <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
        <?php } ?>
		</article>
	</div>
</main>
</body>
</html>
