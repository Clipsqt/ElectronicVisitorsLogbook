<?php
session_start();
require_once("connect.php");

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "e-logsheet";

$conn = mysqli_connect($servername, $username, $password, $dbname);
$userOffice = $_SESSION['userOffice'];
$sql = "SELECT * FROM e_monitoringlogsheet WHERE department = '$userOffice' AND scheduledate  AND reference_no NOT IN (SELECT reference_no FROM e_logshistory)";
$result = mysqli_query($conn, $sql);

$rowNumber = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bookman+Old+Style">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin_webpage.css">
    <title>Monitoring Visitor's Logbook</title>

    <header>
        <img src="monitoring logbook logo.jpeg.png" alt="">
        <h1>APPOINTMENT LIST <br> <?php
            if (isset($_SESSION['userOffice'])) {
                echo $_SESSION['userOffice'];
            }
            ?> </h1>
    </header>
</head>
<body>
<div class="scroll">
    <table id="monitoringTable">
    <tr>
        <th id="colNo">No.</th>
        <th id="colFullName">Full Name</th>
        <th id="colSex">Sex</th>
        <th id="colpriority">Priority</th>
        <th id="colPhoneNumber">Phone Number</th>
        <th id="colScheduleDate">Schedule Date</th>
        <th id="colAppointment">Appointment</th>
        <th id="colPurpose">Purpose of visit</th>
        <th id="colDepartment">Department</th>
        <th id="colreference_no">Reference No.</th>
        <th id="colTime in">Time In</th>
        <th id="colTime ">Time In</th>
        <th>Action</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr id="row_<?php echo $rowNumber; ?>" class="clickable-row">
        <td><?php echo $rowNumber; ?></td>
        <td class="fullname"><?php echo $row["fullname"]; ?></td>
        <td class="sex"><?php echo $row["sex"]; ?></td>
        <td class="priority"><?php echo $row["priority"]; ?></td>
        <td><?php echo $row["phonenumber"]; ?></td>
        <td class="sched"><?php echo $row["scheduledate"]; ?></td>
        <td><?php echo $row["appointment"]; ?></td>
        <td><?php echo $row["purpose_of_visit"]; ?></td>
        <td><?php echo $row["department"]; ?></td>
        <td><?php echo $row["reference_no"]; ?></td>
        <td><?php echo $row["time_in"]; ?></td>
        <td><button id="timeout_button_<?php echo $rowNumber; ?>" class="timeout-button" data-reference="<?php echo $row["reference_no"]; ?>">Time Out</button></td>
    </tr>
    <?php
    $rowNumber++;
    }
    ?>
    </table>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
  <?php
  $rowNumber = 1; // Reset row number for JavaScript
  mysqli_data_seek($result, 0); // Reset the result pointer
  while ($row = mysqli_fetch_assoc($result)) {
  ?>
  document.getElementById("timeout_button_<?php echo $rowNumber; ?>").addEventListener("click", function () {
    var reference_no = this.getAttribute("data-reference");
    
    // Make an AJAX request to transfer the row to another page
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "transfer_row.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          // Handle the response from the server (e.g., display a success sweet alert)
          Swal.fire({
            icon: 'success',
            title: 'Time Out Successful',
            text: 'The time out action was successful!',
          }).then(function () {
            // You can also redirect to another page here if needed
            window.location.href = "admin_webpage.php";
          });
        } else {
          // Handle any error here
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing the request.',
          });
        }
      }
    };
    
    // Send the reference_no as POST data
    xhr.send("reference_no=" + reference_no);
  });
  <?php
  $rowNumber++;
  }
  ?>
});
  
</script>
</body>
</html>
