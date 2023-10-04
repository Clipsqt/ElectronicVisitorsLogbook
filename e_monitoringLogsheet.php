<?php
$api_url = "http://worldtimeapi.org/api/ip";
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

if ($data !== null && isset($data['datetime'])) {
    $currentDate = new DateTime($data['datetime']);
    $formattedDate = $currentDate->format('m-d-Y');
    $scheduledate = $currentDate->format('m/d/Y');
    session_start();
    require_once("connect.php"); // Make sure this file includes database connection code.
    $query = "SELECT * FROM e_monitoringlogsheet WHERE scheduledate = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $scheduledate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rowNumber = 1;
    } else {
        echo "Failed to prepare the statement.";
    }
} else {
    echo "Failed to fetch data from the API.";
}

$filterFullName = '';
$filterDepartment = '';

if (isset($_GET['filterFullName'])) {
    $filterFullName = mysqli_real_escape_string($conn, $_GET['filterFullName']);
}

if (isset($_GET['filterDepartment'])) {
    $filterDepartment = mysqli_real_escape_string($conn, $_GET['filterDepartment']);
}

$query = "SELECT * FROM e_monitoringlogsheet WHERE scheduledate = ?";

if (!empty($filterFullName)) {
    $query .= " AND fullname LIKE ?";
}

if (!empty($filterDepartment)) {
    $query .= " AND department LIKE ?";
}

$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    if (!empty($filterFullName)) {
        $filterFullName = "%" . $filterFullName . "%";
        mysqli_stmt_bind_param($stmt, "ss", $scheduledate, $filterFullName);
    } elseif (!empty($filterDepartment)) {
        $filterDepartment = "%" . $filterDepartment . "%";
        mysqli_stmt_bind_param($stmt, "ss", $scheduledate, $filterDepartment);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $scheduledate);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

$rowNumber = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bookman+Old+Style">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="e_monitoringLogsheet.css">
    <title>Monitoring Visitor's Logbook</title>
</head>
<body>

<header>
    <img src="monitoring logbook logo.jpeg.png" alt="">
    <h1>  E- MONITORING LOG SHEET</h1>
    <input type="text" id="search" placeholder="Search">
</div>
</header>
<div class="scroll-container">
    <div class="scroll"></div>
    <table id="monitoringTable">
        <thead>
        <tr>
            <th id="colNo">No.</th>
            <th id="colFullName">Full Name</th>
            <th id="colSex">Sex</th>
            <th id="colPriority">Priority</th>
            <th id="colPhoneNumber">Phone Number</th>
            <th id="colScheduleDate">Schedule Date</th>
            <th id="colAppointment">Appointment</th>
            <th id="colPurpose">Purpose of visit</th>
            <th id="colDepartment">Department</th>
            <th id="colReference_no">Reference No.</th> 
            <th id="colTimein">Time In</th> 
            
        <?php

        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
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
            
            </tr>
            </thead>
            <?php
           
            $rowNumber++;
        }
        ?>
    </table>
    </div> 
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("search");
    const table = document.getElementById("monitoringTable");
    const rows = table.querySelectorAll("tbody tr");

    function filterRows() {
        const searchTerm = searchInput.value.trim().toLowerCase();

        rows.forEach(function(row) {
            let rowMatch = false;

            row.querySelectorAll("td").forEach(function(cell) {
                const cellValue = cell.textContent.trim().toLowerCase();
                if (cellValue.includes(searchTerm)) {
                    rowMatch = true;
                }
            });

            // Check if the row matches the search term or if no search term is provided (show all rows)
            if (searchTerm === "" || rowMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener("input", filterRows);
});
</script>
 <script>
   document.addEventListener("DOMContentLoaded", function () {
    function sortTable(columnIndex, ascending) {
      // Sorting function remains the same as your original code
      // ...
    }

    // Add event listeners for sorting
    // ...
  });
</script>
 <script>
   document.addEventListener("DOMContentLoaded", function () {
    function sortTable(columnIndex, ascending) {
            let table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("monitoringTable");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                    if (ascending) {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
        
        
        document.getElementById("colNo").addEventListener("click", function () {
            sortTable(0, true);
        });
        document.getElementById("colFullName").addEventListener("click", function () {
            sortTable(1, true);
        });
        document.getElementById("colSex").addEventListener("click", function () {
            sortTable(2, true);
        });
        document.getElementById("colPriority").addEventListener("click", function () {
            sortTable(3, true);
        });
        document.getElementById("colPhoneNumber").addEventListener("click", function () {
            sortTable(4, true);
        });
        document.getElementById("colScheduleDate").addEventListener("click", function () {
            sortTable(5, true);
        });
        document.getElementById("colAppointment").addEventListener("click", function () {
            sortTable(6, true);
        });
        document.getElementById("colPurpose").addEventListener("click", function () {
            sortTable(7, true);
        });
        document.getElementById("colDepartment").addEventListener("click", function () {
            sortTable(8, true);
        });
        document.getElementById("colReference_no").addEventListener("click", function () {
            sortTable(9, true);
        });
        document.getElementById("colTimein").addEventListener("click", function () {
            sortTable(10, true);
        });
       
    });
</script>

</body>
</html>
