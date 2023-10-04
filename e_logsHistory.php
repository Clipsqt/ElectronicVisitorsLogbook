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
        <link rel="stylesheet" href="e_logsHistory.css">
        <title>E-LOGS HISTORY</title>
    </head>
    <header>
        <img src="monitoring logbook logo.jpeg.png" alt="">
        <h1>  E- LOG'S HISTORY</h1>
        <input type autocomplete="off" id="search" placeholder="Search">
    </header>
<body>
    <div class="filter-container">
        <label for="fromDate">From:</label>
        <input type="text" id="fromDate" placeholder="MM/DD/YYYY">
        <label for="toDate">To:</label>
        <input type="text" id="toDate" placeholder="MM/DD/YYYY">
        <button onclick="filterData()">Find</button>
        <button id="downloadexcel" onclick="exportTableToExcel('monitoringTable', 'e_logshistort')">Convert to Excel</button>
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
 <div class="pagination">
    <button id="previousButton">Previous</button>
    <div id="paginationContainer" class="page-numbers"></div>
    <button id="nextButton">Next</button>
    <button id="showAllButton" onclick="showAllRows()">Show All</button>
</div>
    <div class="scroll">    
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
    <script>
  document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("monitoringTable");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    const rowsPerPage = 10;
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    let currentPage = 1;

    function updateTableDisplay() {
        rows.forEach(function (row, index) {
            if (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    function goToNextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            updateTableDisplay();
            updatePageNumber();
        }
    }

    function goToPreviousPage() {
        if (currentPage > 1) {
            currentPage--;
            updateTableDisplay();
            updatePageNumber();
        }
    }

    function goToPage(pageNumber) {
        if (pageNumber >= 1 && pageNumber <= totalPages) {
            currentPage = pageNumber;
            updateTableDisplay();
            updatePageNumber();
        }
    }

    function updatePageNumber() {
        // Get all page number buttons
        const pageButtons = document.querySelectorAll(".page-numbers button");

        // Loop through all page number buttons and remove the "active" class
        pageButtons.forEach(function (button) {
            button.classList.remove("active");
        });

        // Add the "active" class to the current page number button
        const currentPageButton = pageButtons[currentPage - 1];
        currentPageButton.classList.add("active");

        document.getElementById("currentPage").textContent = `Page ${currentPage}`;
    }

    document.getElementById("nextButton").addEventListener("click", goToNextPage);
    document.getElementById("previousButton").addEventListener("click", goToPreviousPage);

    // Create page number buttons
    const paginationContainer = document.getElementById("paginationContainer");
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement("button");
        pageButton.textContent = i;
        pageButton.addEventListener("click", function () {
            goToPage(i);
        });
        paginationContainer.appendChild(pageButton);
    }

    // Initialize table display and page number
    updateTableDisplay();
    updatePageNumber();
});
  
</script>
<script>
  function showAllRows() {
    const table = document.getElementById("monitoringTable");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    
    rows.forEach(function (row) {
      row.style.display = ""; // Display all rows
    });
  }
</script>
    <script>
 document.addEventListener("DOMContentLoaded", function () {
    // ...

    function updatePageNumber() {
        // Get all page number buttons
        const pageButtons = document.querySelectorAll(".page-numbers button");

        // Loop through all page number buttons and remove the "active" class
        pageButtons.forEach(function (button) {
            button.classList.remove("active");
        });

        // Add the "active" class to the current page number button
        const currentPageButton = pageButtons[currentPage - 1];
        currentPageButton.classList.add("active");

        document.getElementById("currentPage").textContent = `Page ${currentPage}`;
    }

    // ...
});
</script>
    </div>
    </body>
    </html>
