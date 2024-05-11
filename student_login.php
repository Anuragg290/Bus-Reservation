<?php
session_start();
require_once './db_connection.php';

// Destroy any existing session data
session_destroy();

// Start a new session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    // Query using student_id
    $sql = "SELECT * FROM student WHERE student_id = '$student_id' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        // Fetch student_id from the result
        $row = mysqli_fetch_assoc($result);
        $student_id = $row['student_id'];

        $_SESSION['student_id'] = $student_id;
        header("location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid student ID or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="./css/login1.css">
</head>
<body>
  <div class="session">
    <div class="left">     
    </div>
    <form method="post"> 
      <h4>Student <span>Login</span></h4>
      <p>Welcome back! Login Here !!</p>
      <div class="floating-label">
      <input type="text" id="student_id" name="student_id" required>
      <label for="student_id">Student ID</label>

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
