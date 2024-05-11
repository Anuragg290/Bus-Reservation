<?php
session_start();
require_once './db_connection.php';

// Destroy any existing session data
session_destroy();

// Start a new session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id'];
    $password = $_POST['password'];

    // Query using staff_id
    $sql = "SELECT * FROM staff WHERE staff_id = '$staff_id' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        // Fetch staff_id from the result
        $row = mysqli_fetch_assoc($result);
        $staff_id = $row['staff_id'];

        $_SESSION['staff_id'] = $staff_id;
        header("location: staff_dashboard.php");
        exit();
    } else {
        $error = "Invalid staff ID or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>staff Login</title>
    <link rel="stylesheet" href="./css/login2.css">
</head>
<body>
  <div class="session">
    <div class="left">
      
    </div>
    <form method="post"> 
      <h4>Staff <span>Login</span></h4>
      <p>Welcome back! Login Here !!</p>
      <div class="floating-label">
      <input type="text" id="staff_id" name="staff_id" required>
      <label for="staff_id">Teacher ID</label>

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

