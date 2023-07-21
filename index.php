<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jira Task Responsive</title>
    <style>
        /* Common styles for all screen sizes */
        .row {
            display: flex;
            align-items: center;
        }
        
        .col {
            margin-right: 20px;
        }

        .col:last-child {
            margin-right: 0;
        }

        .required {
            color: red;
        }

        .submit-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .submit-button {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: darkgreen;
        }

        /* Media query for screens with a maximum width of 768px (for tablets and below) */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .col {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px; /* Add some space between columns in mobile view */
            }

            .submit-container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
        }

        /* Media query for screens with a minimum width of 769px (for laptops and larger) */
        @media (min-width: 769px) {
            .row {
                display: flex;
            }

            .col {
                flex: 1;
                margin-right: 20px;
                margin-bottom: 0;
            }

            .col:last-child {
                margin-right: 0;
            }

            .submit-container {
                display: none; /* Hide the submit button container on larger screens */
            }
        }

        /* Custom styles for the dropdown icon */
        .dropdown-icon {
            cursor: pointer;
            position: absolute;
            right: 30px; /* Adjust the position of the dropdown icon */
            top: 50%;
            transform: translateY(-50%);
        }

        .dropdown-icon::before {
            content: '\25BC';
            font-size: 10px;
        }

        /* Custom styles for the dropdown list */
        .dropdown-list {
            display: none;
            position: absolute;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-height: 150px;
            overflow-y: auto;
            z-index: 1;
            width: 100%;
        }

        .dropdown-list.active {
            display: block;
        }

        .dropdown-list-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-list-item:hover {
            background-color: #f2f2f2;
        }
    </style>
    <!-- Include jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
    <script>
        function fetchEmployeeData(empId) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    if (data) {
                        document.getElementById('emp_id').value = data.empid;
                        document.getElementById('emp_name').value = data.Employee_Full_Name;
                        document.getElementById('team_name').value = data.Team_Name;
                        document.getElementById('team_leader').value = data.Team_Head_Name;
                    } else {
                        alert("No data available for the entered Employee ID.");
                    }
                }
            };
            xhttp.open("GET", "get_employee_data.php?term=" + empId, true);
            xhttp.send();
        }

        // Function to fetch autocomplete suggestions
        $(document).ready(function() {
            $("#emp_id").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "get_suggestions.php",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    // Fetch details when suggestion is clicked
                    var selectedValue = ui.item.value;
                    document.getElementById('emp_id').value = selectedValue;
                    fetchEmployeeData(selectedValue);
                }
            });
        });

        // Function to populate the empid dropdown
        function populateDropdown() {
            $.ajax({
                url: "get_all_empids.php",
                dataType: "json",
                success: function(data) {
                    var dropdownList = $(".dropdown-list");
                    dropdownList.empty();
                    $.each(data, function(index, empid) {
                        dropdownList.append('<div class="dropdown-list-item">' + empid + '</div>');
                    });
                }
            });
        }

        // Handle the selection from the dropdown
        $(document).on("click", ".dropdown-list-item", function() {
            var selectedEmpId = $(this).text();
            if (selectedEmpId !== "") {
                $("#emp_id").val(selectedEmpId);
                fetchEmployeeData(selectedEmpId);
            }
            $(".dropdown-list").removeClass("active");
        });

        // Toggle dropdown list on the dropdown icon click
        $(document).on("click", ".dropdown-icon", function() {
            $(".dropdown-list").toggleClass("active");
        });

        // Call the populateDropdown function on document ready
        $(document).ready(function() {
            populateDropdown();
        });

    </script>
</head>

<body>
    <div class="row">
        <div class="col" style="position: relative;">
            <label for="emp_id">Employee ID<span class="required">*</span>:
                <!-- Dropdown icon -->
                <div class="dropdown-icon"></div>
            </label>
            <input type="text" id="emp_id" name="emp_id" placeholder="Employee ID" required autocomplete="off">
            <!-- Dropdown list -->
            <div class="dropdown-list"></div>
        </div>
        <div class="col">
            <label for="emp_name">Employee Name<span class="required">*</span>:</label>
            <input type="text" id="emp_name" name="emp_name" placeholder="Employee Name" required autocomplete="off">
        </div>
        <div class="col">
            <label for="team_name">Team Name:</label>
            <input type="text" id="team_name" name="team_name" placeholder="Team Name" required autocomplete="off">
        </div>
        <div class="col">
            <label for="team_leader">Team Leader:</label>
            <input type="text" id="team_leader" name="team_leader" placeholder="Team Leader" required autocomplete="off">
        </div>
    </div>
    <div class="submit-container">
        <button class="submit-button" type="button" onclick="fetchEmployeeData($('#emp_id').val())">Submit</button>
    </div>
</body>

</html>
