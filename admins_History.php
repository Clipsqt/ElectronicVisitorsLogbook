<?php
session_start();
require_once("connect.php");

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "e-logsheet";

$conn = mysqli_connect($servername, $username, $password, $dbname);
$userOffice = $_SESSION['userOffice'];

// Modify the SQL query to filter data based on userdepartment in e_logshistory table
$sql = "SELECT * FROM e_logshistory WHERE department = '$userOffice' AND reference_no NOT IN (SELECT reference_no FROM e_logshistory WHERE department = '$userOffice')";
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
        <h1>HISTORY LOGS <br> <?php
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
        <th id="colTime out ">Time out</th>
       
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
        <td><?php echo $row["time_out"]; ?></td>
    </tr>
    <?php
    $rowNumber++;
    }
    ?>
    </table>
</div>
</body>
</html>
