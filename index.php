<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Selection</title>
    <link rel="stylesheet" href="css/indexcss.css">
</head>
<body>
    <div class="container" style="text-align: center;">
        <h2 style="color: black;">Select User Type</h2><br><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <button type="submit" name="user_type" value="type1">Student Login</button><br><br><br>
                <button type="submit" name="user_type" value="type2">staff Login</button><br><br><br>
                <button type="submit" name="user_type" value="type3">Driver Login</button><br>
            </div>
        </form>
    </div>
    <?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $user_type = $_POST['user_type'];


        $_SESSION['user_type'] = $user_type;


        switch ($user_type) {
            case 'type1':
                header("Location: student_login.php");
                break;
            case 'type2':
                header("Location: staff_login.php");
                break;
            case 'type3':
                header("Location: driver_login.php");
                break;
            default:

                header("Location: index.php");
                break;
        }
        exit();
    }
    ?>
</body>
</html>
