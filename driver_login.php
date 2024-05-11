<?php
session_start();
require_once './db_connection.php';

// Destroy any existing session data
session_destroy();

// Start a new session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driver_id = $_POST['driver_id'];
    $password = $_POST['password'];

    // Query using driver_id
    $sql = "SELECT * FROM driver WHERE driver_id = '$driver_id' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        // Fetch driver_id from the result
        $row = mysqli_fetch_assoc($result);
        $driver_id = $row['driver_id'];

        $_SESSION['driver_id'] = $driver_id;
        header("location: driver_dashboard.php");
        exit();
    } else {
        $error = "Invalid driver ID or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Login</title>
    <link rel="stylesheet" href="./css/login3.css">
</head>
<body>
  <div class="session">
    <div class="left">
  
    </div>
    <form method="post"> 
      <h4>Driver <span>Login</span></h4>
      <p>Welcome back! Login Here !!</p>
      <div class="floating-label">
      <input type="text" id="driver_id" name="driver_id" required>
      <label for="driver_id">Driver ID</label>
      </div>
      <div class="floating-label">
         <input type="password" id="password" name="password" required>
         <label for="password">Password</label>
      
        
      </div>
      <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
            <button>Log In</button>
      
    </form>
  </div>

</body>
</html>