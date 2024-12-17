<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Include database configuration
include_once "config.php";

if (isset($_POST['cmnt_msg'])) {
    $msg = $_POST['cmnt_msg'];
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = $_SESSION['user_id'];
    }
    
    $ins_sql = "UPDATE `rides` SET `comments` = '$msg' WHERE `rides`.`id` = '$id'";
    if (mysqli_query($conn, $ins_sql)) {
        $message = "Comment created successfully!";
    } else {
        $message = "Error creating ride: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Ride</title>
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
            <h1 class="card-title text-center">Comment</h1>
            <form action="comment.php" method="post">
              <div class="form-group">
                <label for="pickup_location">User</label>
                <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $id; ?>" required>
                <input type="text" disabled="disabled" class="form-control" id="username" name="username" placeholder="<?php echo $_SESSION['username']?>">
              </div>
              <div class="form-group">
                <label for="exampleTextarea" class="form-label">Your Message</label>
                <textarea class="form-control" name="cmnt_msg" id="exampleTextarea" rows="4" placeholder="Enter your message..."></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-block" name="create_ride">Post Comment</button>
            </form>
            <?php if (isset($message)): ?>
            <div class="alert alert-success mt-3" role="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <p class="text-center mt-3"><a href="dashboard.php">Back to Dashboard</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>