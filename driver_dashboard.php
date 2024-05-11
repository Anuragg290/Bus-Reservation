<?php
session_start();
require_once './db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['driver_id'])) {
    header("location: driver_login.php");
    exit();
}

// Fetch driver details from the database
$driver_id = $_SESSION['driver_id'];
$sql = "SELECT * FROM driver WHERE driver_id = '$driver_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) == 1) {
    $driver = mysqli_fetch_assoc($result);
} else {
    // Redirect to login if user not found
    header("location: driver_login.php");
    exit();
}

// Fetch bus assignments for the driver
$sql_bus_assignments = "SELECT * FROM buses WHERE driver_id = '{$driver['id']}'";
$result_bus_assignments = mysqli_query($conn, $sql_bus_assignments);

$bus_assignments = array();
if ($result_bus_assignments && mysqli_num_rows($result_bus_assignments) > 0) {
    while ($row = mysqli_fetch_assoc($result_bus_assignments)) {
        $bus_assignments[] = $row;
    }
}

// Fetch passengers registered on the driver's buses
$passengers = array();
foreach ($bus_assignments as $bus) {
    $bus_id = $bus['id'];
    $sql_passengers = "SELECT br.*, 
                              CASE 
                                  WHEN br.user_id_student IS NOT NULL THEN s.student_id 
                                  WHEN br.user_id_staff IS NOT NULL THEN st.staff_id 
                                  ELSE 'Unknown' 
                              END AS personal_id,
                              CASE 
                                  WHEN br.user_id_student IS NOT NULL THEN 'Student' 
                                  WHEN br.user_id_staff IS NOT NULL THEN 'Staff' 
                                  ELSE 'Unknown' 
                              END AS passenger_type
                        FROM bus_registrations br
                        LEFT JOIN student s ON br.user_id_student = s.id
                        LEFT JOIN staff st ON br.user_id_staff = st.id
                        WHERE br.bus_id = '$bus_id'";
    $result_passengers = mysqli_query($conn, $sql_passengers);
    
    if ($result_passengers && mysqli_num_rows($result_passengers) > 0) {
        while ($passenger = mysqli_fetch_assoc($result_passengers)) {
            $passengers[] = $passenger;
        }
    }
}

// Handle location data submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $accuracy = $_POST['accuracy'];

    // Delete existing location data for this driver
    $delete_sql = "DELETE FROM LocationData WHERE driver_id = '{$driver['id']}'";
    mysqli_query($conn, $delete_sql);

    // Insert new location data
    $insert_sql = "INSERT INTO LocationData (latitude, longitude, accuracy, driver_id) VALUES ('$latitude', '$longitude', '$accuracy', '{$driver['id']}')";
    mysqli_query($conn, $insert_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>

    <!-- leaflet css  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="./css/driver_dashboard.css">
   
</head>

<body>
<header class="header-outer">
	<div class="header-inner responsive-wrapper">
		<div class="header-logo">
        <h2>Welcome, <?php echo $driver['driver_id']; ?></h2>
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

    <div id="map"></div>
  
    <div class="container1">
         <h3>Driver Information : </h3>
        <p style="font-weight : bold;">Username : <?php echo $driver['driver_id']; ?></p>
        <p style="font-weight : bold;">Email :<?php echo $driver['email']; ?></p><br>
     
     

        <!-- Display passengers information -->
        <h3>Passengers Information : </h3><br>
        <div class="container">
        <ul class="responsive-table">
        <li class="table-header">
      <div class="col col-1"> Id</div>
      
      <div class="col col-3">Passanger Type</div>
    
    </li>
            <?php foreach ($passengers as $passenger) { ?>
                <li class="table-row">
                <div class="col col-1" data-label="Job Id"><?php echo $passenger['personal_id']; ?></div> 
                <div class="col col-3" data-label="Amount"><?php echo $passenger['passenger_type']; ?></div>
                </li>
            <?php } ?>
        </ul>
    </div>

   <!-- Update Location Data Form -->

    <section class="wrapper">
  <div class="content">
    <header>
    <h3>Update Location Data</h3>
    </header>
    <section>
      <p>
        Update Your location so that Student can See Your Location !!
      </p>
    </section>
    <footer class="footer1">
    <form id="locationForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="latitude">Latitude:</label>
            <input type="text" name="latitude" id="latitude" required><br><br>
            <label for="longitude">Longitude:</label>
            <input type="text" name="longitude" id="longitude" required><br><br>
            <label for="accuracy">Accuracy:</label>
            <input type="text" name="accuracy" id="accuracy" required><br><br>
            
        </form>
    </footer>
  </div>
</section>



    
      

    <!-- leaflet js  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Map initialization 
        var map = L.map('map').setView([14.0860746, 100.608406], 6);

        //osm layer
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });
        osm.addTo(map);

        if (!navigator.geolocation) {
            console.log("Your browser doesn't support geolocation feature!")
        } else {
            // Fetch location periodically
            setInterval(() => {
                navigator.geolocation.getCurrentPosition(getPosition)
            }, 5000); // Update every 5 seconds

            // Automatically submit the form after 15 seconds
            setTimeout(() => {
                document.getElementById("locationForm").submit();
            }, 15000);
        }

        var marker, circle;

        function getPosition(position) {
            var lat = position.coords.latitude
            var long = position.coords.longitude
            var accuracy = position.coords.accuracy

            // Fill in the form fields with the fetched values
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = long;
            document.getElementById('accuracy').value = accuracy;

            // Show the map (optional)
            document.getElementById('map').style.display = 'block';

            if (marker) {
                map.removeLayer(marker)
            }

            if (circle) {
                map.removeLayer(circle)
            }

            marker = L.marker([lat, long])
            circle = L.circle([lat, long], { radius: accuracy })

            var featureGroup = L.featureGroup([marker, circle]).addTo(map)

            map.fitBounds(featureGroup.getBounds())

            console.log("Your coordinate is: Lat: " + lat + " Long: " + long + " Accuracy: " + accuracy)
        }
    </script>
		</article>
	</div>
</main>

</body>

</html>
