<?php
session_start();

require_once("connect.php");
date_default_timezone_set('Asia/Manila');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = mysqli_real_escape_string($conn, $_POST["Fullname"]);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST["phonenumber"]);
    $purpose = mysqli_real_escape_string($conn, $_POST["Purpose"]);
    $selectedOffice = mysqli_real_escape_string($conn, $_POST["selectOffice"]);
    $scheduleDate = mysqli_real_escape_string($conn, $_POST["currentDate"]);

    // Retrieve the selected appointment type
    $appointment = mysqli_real_escape_string($conn, $_POST["appointment_type"]);

    // Retrieve the selected sex value
    $sex = mysqli_real_escape_string($conn, $_POST["gender"]);

    // Retrieve the selected priority lane value
    $priorityLane = isset($_POST["priorityLane"]) ? mysqli_real_escape_string($conn, $_POST["priorityLane"]) : '';

    // Set the reference number based on the appointment type
    $reference_no = ($appointment == "Walk-in") ? null : uniqid();

    // Get the current time in the desired format
    $time_in = ($appointment == "Online") ? null : date("h:i A");

    // Validate and sanitize data as needed (you can add more validation here)

    // Prepare and execute an SQL INSERT statement
    $sql = "INSERT INTO e_monitoringlogsheet (appointment, fullname, phonenumber, purpose_of_visit, department, scheduledate, reference_no, sex, priority, time_in) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssss", $appointment, $fullName, $phoneNumber, $purpose, $selectedOffice, $scheduleDate, $reference_no, $sex, $priorityLane, $time_in);

        if (mysqli_stmt_execute($stmt)) {
            // Data inserted successfully, now show the reference number for online appointments
            if ($appointment == "Online") {
                $response = ['reference_no' => $reference_no, 'sex' => $sex, 'priorityLane' => $priorityLane, 'appointment' => $appointment];
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in preparing the SQL statement: " . mysqli_error($conn);
    }
}
?>