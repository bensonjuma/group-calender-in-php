<?php
session_start();
// get the csrf token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- meta crsf token -->
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>Js calendar</title>
    <!-- css -->
    <link rel="stylesheet" href="css/style.css?<?php echo rand(); ?>">
</head>

<body>
    <div class="container">
        <h1>Calendar</h1>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo "<h3>Welcome " . $_SESSION['user_name'] . "</h3>";
        }
        if (isset($_SESSION['user_id'])) {
        ?>
            <div class="logout">
                <a href="logout.php" id="logout">Logout</a>
            </div>
        <?php } ?>
        <div class="auth-form">
            <h3>Login or Register to be able to add events and view</h3>
            <div class="auth-form-forms">
                <div class="register">
                    <form action="" method="post">
                        <!-- name -->
                        <div class="form-control">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="Enter your name">
                            <input type="hidden" name="csrf_token" id="register-crsf" value="<?php echo $_SESSION['csrf_token']; ?>">
                        </div>
                        <!-- email -->
                        <div class="form-control">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email">
                        </div>
                        <!-- password -->
                        <div class="form-control">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter your password">
                        </div>
                        <!-- password confirm -->
                        <div class="form-control">
                            <label for="password2">Confirm Password</label>
                            <input type="password" name="password2" id="password2" placeholder="Confirm your password">
                        </div>
                        <!-- submit -->
                        <div class="form-control">
                            <input type="submit" value="Register" name="register" id="register">
                        </div>
                    </form>
                </div>
                <!-- nw -->
                <div class="login">
                    <form action="" method="post">
                        <!-- email -->
                        <div class="form-control">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="login-email" placeholder="Enter your email">
                            <input type="hidden" name="csrf_token" id="login-crsf" value="<?php echo $_SESSION['csrf_token']; ?>">
                        </div>
                        <!-- password -->
                        <div class="form-control">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="login-password" placeholder="Enter your password">
                        </div>
                        <!-- submit -->
                        <div class="form-control">
                            <input type="submit" value="Login" name="login" id="login">
                        </div>
                    </form>
                </div>
            </div>
            <!-- nw -->
        </div>
        <div class="calendar-table">
            <div class="c-header">

                <div class="previous-year">
                    <a href="">previous</a>
                </div>
                <div class="big-year" id="yearNum">
                    <a href="">year</a>
                </div>
                <div class="next-year">
                    <a href="">Next year </a>
                </div>
            </div>
            <div class="months">
                months
            </div>

            <hr class="month-line" />
            <table class="calendar-table" id="calendar">
                <thead>
                    <tr>
                        <th class="days-of-week">Sun</th>
                        <th class="days-of-week">Mon</th>
                        <th class="days-of-week">Tue</th>
                        <th class="days-of-week">Wed</th>
                        <th class="days-of-week">Thu</th>
                        <th class="days-of-week">Fri</th>
                        <th class="days-of-week">Sat</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                </tbody>
            </table>
        </div>
        <!-- nw -->
        <!-- add event -->
        <div class="add-event">
            <form action="" method="post">
                <!-- title -->
                <div class="form-control">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="event-title" placeholder="Enter event title">
                    <!-- csrf token -->
                    <input type="hidden" name="csrf_token" id="add-event-crsf" value="<?php echo $_SESSION['csrf_token']; ?>">
                </div>
                <!-- description -->
                <div class="form-control">
                    <label for="description">Description</label>
                    <textarea name="description" id="event-description" cols="20" rows="3" placeholder="Enter event description"></textarea>
                </div>
                <!-- time -->
                <div class="form-control">
                    <label for="time">Time</label>
                    <input type="time" name="time" id="event-time">
                    <!-- date -->
                    <div class="form-control">
                        <label for="date">Date</label>
                        <input type="text" name="date" id="event-date" readonly>
                    </div>
                    <!-- submit -->
                    <div class="form-control">
                        <input type="submit" value="Add Event" name="add-event" id="add-event">
                        <!-- delete event -->
                        <input type="submit" value="Delete Event" name="delete-event" id="delete-event">
                    </div>
            </form>
        </div>
    </div>

    </div>
    <!-- calendar js -->
    <script src="js/calendar.js?<?php echo rand(); ?>"></script>
    <script>
        // register on click 
        document.getElementById("register").addEventListener("click", function(e) {
            e.preventDefault();
            // get form data
            let name = document.getElementById("name").value;
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let password2 = document.getElementById("password2").value;
            let csrfToken = document.getElementById("register-crsf").value;
            // check if all fields are filled
            if (name == "" || email == "" || password == "" || password2 == "") {
                alert("Please fill all fields");
            } else {
                // check if passwords match
                if (password != password2) {
                    alert("Passwords do not match");
                } else {
                    // send the AJAX request
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "register.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (this.status == 200) {
                            console.log(this.responseText);
                            // if response contains success
                            if (this.responseText.includes("success")) {
                                alert("Registration successful");
                                // hide the register form
                                document.querySelector(".register").style.display = "none";
                            } else {
                                alert("Registration failed");
                            }
                        }
                        // run checkLogin function
                        checkLogin();
                    }
                    xhr.send("name=" + name + "&email=" + email + "&password=" + password + "&csrf_token=" + csrfToken);
                }
            }
        });
        // login on click
        document.getElementById("login").addEventListener("click", function(e) {
            e.preventDefault();
            // get form data
            let email = document.getElementById("login-email").value;
            let password = document.getElementById("login-password").value;
            let csrfToken = document.getElementById("login-crsf").value;
            // check if all fields are filled
            if (email == "" || password == "") {
                alert("Please fill all fields");
            } else {
                // send the AJAX request
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "login.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status == 200) {
                        console.log(this.responseText);
                        // if response contains success
                        if (this.responseText.includes("success")) {
                            alert("Login successful");
                            // hide the login form
                            document.querySelector(".login").style.display = "none";
                        } else {
                            alert("Login failed");
                        }
                        // run the check login function
                        checkLogin();
                    }
                }
                xhr.send("email=" + email + "&password=" + password + "&csrf_token=" + csrfToken);
            }
        });
        // function to check if user is logged in
        function checkLogin() {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "check_login.php", true);
            xhr.onload = function() {
                if (this.status == 200) {
                    console.log(this.responseText);
                    // if response contains success
                    if (this.responseText.includes("success")) {
                        // hide the auth-form
                        document.querySelector(".auth-form").style.display = "none";
                        // set a cookie to shoe user is logged in
                        document.cookie = "loggedIn=true";
                    } else {
                        // hide the register form
                        console.log("not logged in");
                    }
                }
            }
            xhr.send();
        }
        // call the function
        checkLogin();

        // add-event on click
        document.getElementById("add-event").addEventListener("click", function(e) {
            e.preventDefault();
            // get form data
            let title = document.getElementById("event-title").value;
            let time = document.getElementById("event-time").value;
            let date = document.getElementById("event-date").value;
            let description = document.getElementById("event-description").value;
            let csrfToken = document.getElementById("add-event-crsf").value;
            // check if all fields are filled
            if (title == "" || time == "" || date == "") {
                alert("Please fill all fields");
            } else {
                // send the AJAX request
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "add_event.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status == 200) {
                        console.log(this.responseText);
                        // if response contains success
                        if (this.responseText.includes("success")) {
                            alert("Event added successfully");
                            // hide the add-event form
                            document.querySelector(".add-event").style.display = "none";
                            // clear the add-event completed form
                            document.getElementById("event-title").value = "";
                            document.getElementById("event-time").value = "";
                            document.getElementById("event-date").value = "";
                            document.getElementById("event-description").value = "";
                            // fetch the events again
                            fetchEvents();
                            // reload the calendar
                            renderCalendar(currentMonth, currentYear);

                        } else {
                            alert("Event not added");
                        }
                    }
                }
                xhr.send("title=" + title + "&time=" + time + "&date=" + date + "&description=" + description + "&csrf_token=" + csrfToken)
            }
        });
        // logout on click
        document.getElementById("logout").addEventListener("click", function(e) {
            e.preventDefault();
            // send the AJAX request
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "logout.php", true);
            xhr.onload = function() {
                if (this.status == 200) {
                    console.log(this.responseText);
                    // checkLogin();
                    checkLogin();
                    // destroy the cookie
                    const cookies = document.cookie.split(";");

                    for (let i = 0; i < cookies.length; i++) {
                        const cookie = cookies[i];
                        const eqPos = cookie.indexOf("=");
                        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
                    }

                    renderCalendar(currentMonth, currentYear);

                }
            }
            xhr.send();
        });
    </script>
</body>

</html>