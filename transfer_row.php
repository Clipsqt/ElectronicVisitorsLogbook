<?php
session_start();
require_once("connect.php");

if (isset($_POST['reference_no'])) {
    $reference_no = mysqli_real_escape_string($conn, $_POST['reference_no']);

    // Retrieve the row data from the first table
    $query = "SELECT * FROM e_monitoringlogsheet WHERE reference_no = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $reference_no);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Insert the row data into the second table (e_logsHistory)
        $insertQuery = "INSERT INTO e_logsHistory (fullname, sex, priority, phonenumber, scheduledate, appointment, purpose_of_visit, department, reference_no, time_in, time_out) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssssssssss", $row['fullname'], $row['sex'], $row['priority'], $row['phonenumber'], $row['scheduledate'], $row['appointment'], $row['purpose_of_visit'], $row['department'], $row['reference_no'], $row['time_in'], $row['time_out']);

        if (mysqli_stmt_execute($stmt)) {
            // Delete the row from the first table
            $deleteQuery = "DELETE FROM e_monitoringlogsheet WHERE reference_no = ?";
            $stmt = mysqli_prepare($conn, $deleteQuery);
            mysqli_stmt_bind_param($stmt, "s", $reference_no);
            if (mysqli_stmt_execute($stmt)) {
                echo "Success";
                exit;
            } else {
                echo "Error deleting row: " . mysqli_error($conn);
            }
        } else {
            echo "Error transferring row data: " . mysqli_error($conn);
        }
    } else {
        echo "Row data not found.";
    }
} else {
    echo "Invalid request.";
}
?>