<?php
    session_start();
    require_once("connect.php");

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "e-logsheet";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // Initialize filter variables
    $searchTerm = "";
    $fromDate = "";
    $toDate = "";

    // Check if the search term is provided
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
    }

    // Check if the "From" and "To" dates have been provided
    if (isset($_POST['fromDate']) && isset($_POST['toDate'])) {
        $fromDate = $_POST['fromDate'];
        $toDate = $_POST['toDate'];
    }

    // Modify the SQL query to include the filters
    $query = "SELECT * FROM e_logsHistory WHERE 1=1"; // Start with a true condition

    if (!empty($searchTerm)) {
        $query .= " AND (fullname LIKE '%$searchTerm%' OR scheduledate LIKE '%$searchTerm%')";
    }

    if (!empty($fromDate) && !empty($toDate)) {
        $query .= " AND scheduledate BETWEEN '$fromDate' AND '$toDate'";
    }

    $query .= " ORDER BY id DESC, scheduledate DESC";

    $result = mysqli_query($conn, $query);
    
    $rowNumber = 1; // Initialize row number
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bookman+Old+Style">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src="table2excel.js"></script>
        <link rel="stylesheet" href="try.css">
        <title>E-LOGS HISTORY</title>
    </head>
    <body>
    <header>
    <input type autocomplete="off" id="search" placeholder="Search">
        <img src="monitoring logbook logo.jpeg.png" alt="">
        <h1>  E- LOG'S HISTORY</h1>
    </header>
    
</div>
<input type="checkbox" name="" id="check">
    <div class="container">
     <label for="check">
         <span class="bx bx-x" id="cross"></span>
         <span class="bx bx-menu" id="bars"></span>
     </label>
     <div class="head">MENU</div>
     <ol>
     <li> <a href="#"><i class='bx bx-history'></i>E-LOG'S HISTORY</a></li>
     <li> <a href="#"><i class='bx bxs-notepad'></i></i>ONLINE LOG'S</a></li>
     <li> <a href="#"><i class='bx bx-walk'></i></i>WALK-IN LOG'S</a></li>
     </ol>
 </div>   
 </div>  
 
    <div class="scroll">    
        <div class="table-container"></div>
        <table id="monitoringTable">
        <thead> 
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
            <th id="colTime out">Time Out</th>
        </tr>
    </thead>   
        <?php
        
        
    $query = "SELECT * FROM e_logsHistory ORDER BY id DESC, scheduledate DESC";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $totalRows = mysqli_num_rows($result); // Get the total number of rows
        $rowNumber = $totalRows; // Initialize row number to the total number of rows

        while ($row = mysqli_fetch_assoc($result)) {
            // Start a new table row for each record
            echo "<tr>";
            echo "<td>" . $rowNumber . "</td>";
            echo "<td class='fullname'>" . $row['fullname'] . "</td>";
            echo "<td class='sex'>" . $row['sex'] . "</td>";
            echo "<td class='priority'>" . $row['priority'] . "</td>";
            echo "<td>" . $row['phonenumber'] . "</td>";
            echo "<td class='sched'>" . $row['scheduledate'] . "</td>";
            echo "<td>" . $row['appointment'] . "</td>";
            echo "<td>" . $row['purpose_of_visit'] . "</td>";
            echo "<td>" . $row['department'] . "</td>";
            echo "<td>" . $row['reference_no'] . "</td>";
            echo "<td>" . $row['time_in'] . "</td>";
            echo "<td>" . $row['time_out'] . "</td>";
            echo "</tr>";
            $rowNumber--; // Decrease row number for the next row
        }
    } else {
        echo "No transferred row data found.";
    }
    ?>
        
    </table>
    <button id="downloadexcel" onclick="exportTableToExcel('monitoringTable', 'e_logshistort')">Convert to Excel</button>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const table = document.getElementById("monitoringTable");
        const rows = Array.from(table.querySelectorAll("tbody tr"));

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
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const fromDateInput = document.getElementById("fromDate");
        const toDateInput = document.getElementById("toDate");
        const table = document.getElementById("monitoringTable");
        const rows = Array.from(table.querySelectorAll("tbody tr"));

        function formatDateToMMDDYYYY(dateString) {
            const parts = dateString.split('/'); // Split MM/DD/YYYY into an array
            const month = parts[0];
            const day = parts[1];
            const year = parts[2];
            return `${month}/${day}/${year}`; // Convert to MM/DD/YYYY
        }

        function filterRows() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const fromDate = fromDateInput.value.trim();
            const toDate = toDateInput.value.trim();
            const formattedFromDate = formatDateToMMDDYYYY(fromDate);
            const formattedToDate = formatDateToMMDDYYYY(toDate);

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
                    // Check if the row matches the date range filter or if no dates are selected
                    const scheduleDateCell = row.cells[5]; // Assuming the scheduledate is in the 6th cell
                    const scheduleDate = scheduleDateCell.textContent.trim();
                    
                    if (fromDate === "" || toDate === "" || (scheduleDate >= formattedFromDate && scheduleDate <= formattedToDate)) {
                        row.style.display = ""; // Display the row if it's within the range or no dates selected
                    } else {
                        row.style.display = "none"; // Hide the row if it's outside the range
                    }
                } else {
                    row.style.display = "none"; // Hide the row if it doesn't match the search term
                }
            });
        }

        searchInput.addEventListener("input", filterRows);
        fromDateInput.addEventListener("input", filterRows);
        toDateInput.addEventListener("input", filterRows);
    });
    </script>
    <script>
        function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'e_logshistory.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
    </script>
    </div>
    </div>
    </body>
    </html>
