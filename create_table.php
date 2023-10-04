<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "e-logsheet";

// Create a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL statement to add the 'displayed_at' column
$sql = "ALTER TABLE e_monitoringlogsheet ADD COLUMN displayed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";

// Execute the SQL statement
if (mysqli_query($conn, $sql)) {
    echo "Table altered successfully.";
} else {
    echo "Error altering table: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>