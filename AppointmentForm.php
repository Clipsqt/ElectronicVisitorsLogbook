<?php
    session_start();

    require_once("connect.php");
    $query = "select * from e_monitoringlogsheet";
    $result = mysqli_query($conn,$query);
?>

<!DOCTYPE html> 
<html lang="en">
    
<head> 
   <meta charset="UTF-8">
   
   <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital@0;1&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="AppointmentForm.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
 

<title>Fillup Form</title>

</head>
<body> 
<div class="container">
    <div class="card">
        <div class="inner-box">
            <div class="card-front">
                <h1> APPOINTMENT</h1>
        <form action="Appointment.php" method="post">
            <!-- ... (your form inputs) ... -->
<div class="appointment-type">
    <label>
        <input type="radio" class="radioOnline" name="appointment_type" value="Online" required> Online
    </label>
    <label>
        <input type="radio" class="radioWalkIn" name="appointment_type" value="Walk-in" required> Walk-in
    </label>
</div>
<div id="dateBoxContainer">
    <h2>Prefer Date</h2>
    <input type="text" class="currentDate" name="currentDate" id="currentDate" placeholder="MM/DD/YY" readonly required autocomplete="OFF">
</div>
  <!-- ... (your form inputs) ... -->
 <input type="text" class="input-box" name="Fullname" placeholder="Enter your name" required autocomplete="off" pattern="[A-Za-z\s]+">
                    <input type="tel" class="input-box" name="phonenumber" placeholder="Enter your number" required autocomplete="OFF" pattern="[0-9]{11}" maxlength="11">
                    <script>
                       var quantityInput = document.querySelector('.qu');
                        var quantityInputs = document.querySelectorAll('.quantityInputUser');
                        quantityInputs.forEach(function (quantityInput) {
                            quantityInput.addEventListener('keydown', function (e) {
                                // Allow the backspace key (keyCode 😎 and delete key (keyCode 46)
                                if (e.key !== 'Backspace' && e.key !== 'Delete' && e.keyCode !== 8 && e.keyCode !== 46) {
                                    e.preventDefault();
                                }
                            });
                        });
                    </script>
                 <div class="PurposeBox">  
                    <div class="inner-box">
                    <input type="text" class="input-box" name="Purpose" placeholder="What is your purpose?" required autocomplete="off">  
                    </div> 
                 </div>  
                 <div class="selecOffice">
                    <!-- ... (select input) ... -->
            <div class="Selectoffice">
                <select name="selectOffice" id="selectOffice" required>
                    <option value=""disabled selected>Select Office</option>
                    <option value="School Governance Operations Division">SGOD</option>
                    <option value="Information Communication Technology Services">ICT Services</option>
                    <option value="Office of the Schools Division Superintendent">OSDS</option>
                    <option value="Office of the Assistant Schools Division Superintendent Administrative Services">ASDS</option>
                    <option value="Personnel Section">Personal Section</option>
                    <option value="Records Section">Records Section</option>
                    <option value="Cash Section">Cash Section</option>
                    <option value="Payroll Section">Payroll Section</option>
                    <option value="Support Staff">Support Staff</option>
                    <option value="Property and Supply Section">Property & Supply Section</option>
                    <option value="General Services">General Services</option>
                    <option value="OSDS Proper">OSDS Proper</option>
                    <option value="Legal Services">Legal Services</option>
                    <option value="Accounting Section">Accounting Section</option>
                    <option value="Bugdet Section">Bugdet Section</option>
                   
                
                </select>
                <form> 
                    <div> 
    <label for="gender"> </label>
    <select name="gender" id="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
</select>
</div>
        <div> 
    <label for="priorityLane">Priority Lane</label>
    <select name="priorityLane" id="priorityLane">
        <option value="">Select Lane</option>
        <option value="Senior Citizen">Senior Citizen</option>
        <option value="Pregnant">Pregnant</option>
        <option value="Disable">Disable</option>
    </select>
</div>
    <input type="hidden" name="timeIn" id="timeIn" value="">

        </div>
        </div>  
                 <div class="submit-btn">
                 <button type="submit" class="submit-btn">SUBMIT</button>

                 <input type="hidden" class="reference_no" name="reference_no" readonly>
                 </div>
             
 
 <script>
        document.addEventListener("DOMContentLoaded", function() {
    const customSelect = document.querySelector('.custom-select');

    if (customSelect) {
        // Check if the element exists before adding an event listener
        customSelect.addEventListener('change', () => {
            customSelect.classList.remove('invalid');
        });
    }

    const form = document.querySelector('form');

    form.addEventListener('submit', (e) => {
        if (customSelect && customSelect.value === '') {
            customSelect.classList.add('invalid');
            e.preventDefault();
        }
    });
});

</script>
   
    
    <script>
       $(document).ready(function() {
    $.ajax({
        url: 'https://worldtimeapi.org/api/ip',
        dataType: 'json',
        success: function(data) {
            const onlineDate = new Date(data.utc_datetime);
            const formattedDate = getFormattedDate(onlineDate);
            document.querySelector('.currentDate').value = formattedDate;
        }
    });

    function getFormattedDate(date) {
        const day = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
        const month = (date.getMonth() + 1) < 10 ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1);
        const year = date.getFullYear();
        return `${month}/${day}/${year}`; // Use backticks and ${} to insert variables
    
    }
    //TIMEZONEMANILA//
    const currentTime = new Date();
    const timezoneOffset = currentTime.getTimezoneOffset();
    const formattedTime = currentTime.toISOString().slice(11, 19); // Extract HH:mm:ss

    document.getElementById('timeIn').value = formattedTime + '|' + timezoneOffset;


});

    
    // Get the appointment type radio buttons and the date input box container
    const radioOnline = document.querySelector('.radioOnline');
    const radioWalkIn = document.querySelector('.radioWalkIn');
    const dateBoxContainer = document.getElementById('dateBoxContainer');
    const currentDateInput = document.querySelector('.currentDate');

    // Add an event listener to the radio buttons
    radioOnline.addEventListener('change', function () {
        if (this.checked) {
            // If "Online" is selected, show the date input box and make it editable
            dateBoxContainer.style.display = 'block';
            currentDateInput.readOnly = false;
        }
    });

    radioWalkIn.addEventListener('change', function () {
        if (this.checked) {
            // If "Walk-in" is selected, hide the date input box and set its value to the current date
            dateBoxContainer.style.display = 'none';
            currentDateInput.value = getFormattedDate(new Date());
        }
    });

    // Function to get formatted date (similar to your existing code)
    function getFormattedDate(date) {
        const day = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
        const month = (date.getMonth() + 1) < 10 ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1);
        const year = date.getFullYear();
        return `${month}/${day}/${year}`;
    }
    
</script>
    </script>

    </form>

    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



</script>
<script>
    // Get the input element by its name
    const phoneNumberInput = document.querySelector('input[name="phonenumber"]');

    // Add an input event listener to validate and format the phone number
    phoneNumberInput.addEventListener('input', function (e) {
        // Remove non-numeric characters using a regular expression
        const numericValue = this.value.replace(/\D/g, '');

        // Limit the length of the phone number to 11 digits
        if (numericValue.length > 11) {
            this.value = numericValue.slice(0, 11);
        } else {
            this.value = numericValue;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Get the input element by its name
    const fullnameInput = document.querySelector('input[name="Fullname"]');

    // Add an input event listener to validate and format the name
    fullnameInput.addEventListener('input', function (e) {
        // Remove non-alphabetic characters using a regular expression
        const alphabeticValue = this.value.replace(/[^A-Za-z\s]/g, '');

        // Update the input value with the alphabetic characters
        this.value = alphabeticValue;
    });
    // Assuming you have included the SweetAlert library in your HTML

// Add an event listener to the form for submission
// Add an event listener to the form for submission
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        try {
            const response = await fetch('Appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded', // Change to 'application/json' if sending JSON data
                },
                body: new URLSearchParams(new FormData(form)),
            });

            if (!response.ok) {
                throw new Error(`Network response was not ok. Status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();

                // Check if the reference number exists in the response data
                if (data.reference_no) {
                    // Check if the appointment type is "Online"
                    if (data.appointment && data.appointment.toLowerCase() === 'online') {
                        // Show SweetAlert with the fetched reference number
                        Swal.fire({
                            title: `Your reference number is: ${data.reference_no}`,
                            text: `TAKE A SCREENSHOT & PRESENT TO THE GUARD`,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });

                        // Reset the form or perform any other necessary actions
                        form.reset();
                    }
                } else {
                    // Handle the case where no reference number is returned
                    console.error('No reference number found in the response.');
                }
            } else {
                // Handle non-JSON responses here (e.g., show a success message or reset the form)
                console.log('Response is not in JSON format. Proceeding with form reset or other actions.');
                form.reset();
            }

        } catch (error) {
            console.error('Error fetching reference number:', error.message);
            console.log('Full response:', error.response);  // Use error.response instead of response
        }
    });
});







</script>

 





   

   

</body>
</html>