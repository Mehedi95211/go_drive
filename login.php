<?php
// Start session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}

// Include database configuration
include_once "config.php";

// Handle login logic
if (isset($_POST['login'])) {
  // Retrieve form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check user credentials
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    if ($password == $row['password']) {
      // Set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['user_type'] = $row['user_type'];

      // Redirect to dashboard
      header("Location: dashboard.php");
      exit();
    } else {
      $login_error = "Incorrect password";
    }
  } else {
    $login_error = "Username not found";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ride Sharing App - Log In</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
</head>
<body class="bg-light">
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title text-center">Log In</h1>
            <form action="login.php" method="post">
              <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block" name="login">Log In</button>
            </form>
            <?php if (isset($login_error)): ?>
            <div class="alert alert-danger mt-3" role="alert"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign up here</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
