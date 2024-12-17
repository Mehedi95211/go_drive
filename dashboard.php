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

//Retrieve user information
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM Users WHERE id = '$user_id'";
$result_user = mysqli_query($conn, $sql_user);
$row_user = mysqli_fetch_assoc($result_user);


$user_id = $_SESSION['user_id'];

if($_SESSION['user_type'] == 'rider') {
  $sql_rides = "SELECT * FROM rides WHERE user_id = '$user_id'";
}
if($_SESSION['user_type'] == 'driver') {
  $sql_rides = "SELECT * FROM rides WHERE driver_id = '$user_id'";
}
$result_rides = mysqli_query($conn, $sql_rides);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ride Sharing App - Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
</head>
<body class="bg-light">
  <div class="container">
    <h1 class="mt-5 text-center">Welcome, <?php echo $row_user['username']; ?>!</h1>
    <h2 class="text-center">User Dashboard</h2>
	<div class="d-flex justify-content-between mt-5">
	  <h3 class="">Your Rides(<?php echo $_SESSION['user_type']; ?>)</h3>
	  <a href="newride.php" class="btn btn-primary">Create New Ride</a>
	</div>
    <?php if (mysqli_num_rows($result_rides) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered mt-3">
        <thead class="thead-dark">
          <tr>
            <th>Ride ID</th>
			      <th>Rider ID</th>
            <?php if($_SESSION['user_type'] == 'rider') { echo '<th>Driver</th>'; } else { echo '<th>Passenger</th>'; } ?>
            <th>Pickup Location</th>
            <th>Dropoff Location</th>
            <th>Ride Status</th>
            <th>Ride Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row_ride = mysqli_fetch_assoc($result_rides)): ?>
          <tr>
            <td><?php echo $row_ride['id']; ?></td>
            <td><?php echo $row_ride['user_id']; ?></td>
            <td>
              <?php if($_SESSION['user_type'] == 'rider') { $id =  $row_ride['driver_id']; } else { $id =  $row_ride['user_id']; }
              echo get_user_or_driver($id, $conn); ?>
            </td>
            <td><?php echo $row_ride['pickup_location']; ?></td>
            <td><?php echo $row_ride['dropoff_location']; ?></td>
            <td><?php echo $row_ride['ride_status']; ?></td>
            <td><?php echo $row_ride['create_date']; ?></td>
            <?php if($_SESSION['user_type'] == 'rider') { ?>
            <td><?php if($row_ride['comments'] == '') { ?> <p class="text-center"><a href="comment.php?id=<?php echo $row_ride['id']; ?>" class="btn btn-primary">Comment</a></p> <?php } else echo $row_ride['comments']; ?></td>
            <?php } else { ?>
              <td><?php if($row_ride['ride_status'] == 'pending') { ?><p class="text-center"><a href="complete_ride.php?close_id=<?php echo $row_ride['id']; ?>" class="btn btn-primary">Close Ride</a></p> <?php } else echo $row_ride['comments']; ?></td>
            <?php } ?>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <p>No rides found.</p>
    <?php endif; ?>
    <p class="text-center"><a href="logout.php" class="btn btn-danger">Log out</a></p>
  </div>
</body>
</html>

<?php
function get_user_or_driver($id, $conn) {
  $sql_user = "SELECT * FROM Users WHERE id = '$id'";
  $result_user = mysqli_query($conn, $sql_user);
  $row_user = mysqli_fetch_assoc($result_user);
  return $row_user['username'];
}
?>


