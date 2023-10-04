<script>
document.addEventListener("DOMContentLoaded", function () {
  var currentDate = new Date();
  var dd = String(currentDate.getDate()).padStart(2, '0');
  var mm = String(currentDate.getMonth() + 1).padStart(2, '0'); // January is 0!
  var yyyy = currentDate.getFullYear();

  var formattedDate = mm + '/' + dd + '/' + yyyy;

  // Now, you can use the formattedDate in your SQL query
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "filter_data.php?date=" + formattedDate, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Handle the response from the server (e.g., update the table with new data)
      var response = xhr.responseText;
      document.getElementById("monitoringTable").innerHTML = response;
    }
  };

  xhr.send();
});
</script>
