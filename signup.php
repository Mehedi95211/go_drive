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


// Handle sign up logic
if (isset($_POST['signup'])) {
  // Retrieve form data
  $username = $_POST['username'];
  $password = $_POST['password'];
  $phone_number = $_POST['phone_number'];
  $email = $_POST['email'];
  $user_type = $_POST['user_type'];
  $status = 1;
  $create_date = date('d-m-Y H:i:s');

  $sql_user = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `user_type`, `phone_number`, `status`, `create_date`, `update_date`) VALUES ('', '$username', '$email', '$password', '$user_type', '$phone_number', '$status', '$create_date', NULL)";
  
  if (mysqli_query($conn, $sql_user)) {
    $usr_id = mysqli_insert_id($conn);

    if($user_type = $_POST['user_type'] == 'driver') {
      $license_number = $_POST['license_number'];
      $car_model = $_POST['car_model'];
      $car_registration = $_POST['car_registration_number'];
      $ins_sql = "INSERT INTO `drivers` (`id`, `user_id`, `username`,  `license_number`, `car_model`, `car_registration`, `status`, `create_date`, `update_date`) VALUES (NULL, '$usr_id', '$username', '$license_number', '$car_model', '$car_registration', '1', '$create_date', NULL)";
      mysqli_query($conn, $ins_sql);
    }
    $signup_message = "User signed up successfully!";
  } else {
    $signup_message = "Error: " . $sql_user . "<br>" . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ride Sharing App - Sign Up</title>
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
            <h1 class="card-title text-center">Sign Up</h1>
            <form action="signup.php" method="post">
              <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" required>
              </div>
              <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
              </div>
              <div class="form-group">
                <select class="form-control" name="user_type" required>
                  <option value="">Select User Type</option>
                  <option value="rider">Sign Up as a Rider</option>
                  <option value="driver">Sign Up as a Driver</option>
                </select>
              </div>
              <div id="driver-details" style="display: none;">
                <div class="form-group">
                  <input type="text" class="form-control" name="license_number" placeholder="License Number">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="car_model" placeholder="Car Model">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="car_registration_number" placeholder="Car Registration Number">
                </div>
              </div>
              <div id="rider-details" style="display: none;">
                <div class="form-group">
                  <select class="form-control" name="payment_method">
                    <option value="">Select Payment Method</option>
                    <option value="Cash">Cash</option>
                    <option value="Bkash">Bkash</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Card">Card</option>
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block" name="signup">Sign Up</button>
            </form>
            <?php if (isset($signup_message)): ?>
            <div class="alert alert-success mt-3" role="alert"><?php echo $signup_message; ?></div>
            <?php endif; ?>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Log in here</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Show/hide additional fields based on selected user type
    document.querySelector('select[name="user_type"]').addEventListener('change', function() {
      var userType = this.value;
      if (userType === 'driver') {
        document.getElementById('driver-details').style.display = 'block';
        document.getElementById('rider-details').style.display = 'none';
      } else if (userType === 'rider') {
        document.getElementById('driver-details').style.display = 'none';
        document.getElementById('rider-details').style.display = 'block';
      } else {
        document.getElementById('driver-details').style.display = 'none';
        document.getElementById('rider-details').style.display = 'none';
      }
    });
  </script>
</body>
</html>
