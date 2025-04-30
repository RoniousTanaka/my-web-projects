<?php
// Include the database connection
include 'connectiondb.php';

// Fetch full names from the database
$query = "SELECT fullname FROM users"; //
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Database</title>
    <style>
        body {
            font-family: tahoma;
            background-color: white;
            display: flex;
            margin: 0; /* Remove default margin */
            flex-direction: column; /* Stack elements vertically */
        }
        .container {
            flex: 1; /* Take up remaining space */
            max-width: 1200px;
            margin: 40px auto; /* Center the container */
            display: flex;
            flex-wrap: wrap; /* Allow wrapping of child elements */
            size: 14px;
            border-radius: 95px;
        }
        .our-team {
            padding: 30px 0 40px;
            margin: 10px;
            background: rgba(247, 245, 236, 0.8); /* Slightly transparent background */
            text-align: center;
            overflow: hidden;
            position: relative;
            flex: 1 1 calc(25% - 20px); /* 4 items per row */
            box-sizing: border-box;
            backdrop-filter: blur(10px); /* Glass effect */
            border-radius: 10px; /* Rounded corners */
        }

        .our-team .picture {
            display: inline-block;
            height: 130px;
            width: 130px;
            margin-bottom: 50px;
            z-index: 1;
            position: relative;
        }

        .our-team .picture::before {
            content: "";
            width: 100%;
            height: 0;
            border-radius: 50%;
            background-color: rgba(246, 128, 128, 0.6); /* Neon pink with transparency */
            position: absolute;
            bottom: 135%;
            right: 0;
            left: 0;
            opacity: 0.9;
            transform: scale(3);
            transition: all 0.3s linear 0s;
        }

        .our-team:hover .picture::before {
            height: 100%;
        }

        .our-team .picture img {
            width: 100%;
            height: auto;
            border-radius: 50%;
            transform: scale(1);
            transition: all 0.9s ease 0s;
        }

        .our-team:hover .picture img {
            box-shadow: 0 0 0 14px rgba(247, 245, 236, 0.8); /* Adjusted for clarity */
            transform: scale(0.7);
        }

        .our-team .title {
            display: block;
            font-size: 15px;
            color: #4e5052;
            text-transform: capitalize;
        }
        @media (max-width: 768px) {
            .our-team {
                flex: 1 1 calc(50% - 20px); /* 2 items per row */
            }
        }
        @media (max-width: 576px) {
            .our-team {
                flex: 1 1 100%; /* 1 item per row */
            }

        }

  .sidebar .button {
    display: horizontal; /* Buttons stack vertically */
    width: 100%; /* Responsive width */
    max-width: 250px; /* Limit button size */
    margin: 10px auto; /* Add spacing between buttons */
    height: 50px;
    color: black;
    font-size: 18px;
    letter-spacing: 1px;
    font-weight: 600;
    border-radius: 25px;
    text-align: center; /* Center text */
    border: none; /* Remove border */
    background-color: #eaeaea; /* Button background */
    transition: background-color 0.3s ease; /* Hover effect */
  }

  .sidebar .button:hover {
    background-color: #ccc; /* Change color on hover */
    cursor: pointer;
  }
  .sidebar .input {
    display: horizontal; /* Buttons stack vertically */
    width: 100%; /* Responsive width */
    max-width: 250px; /* Limit button size */
    margin: 10px auto; /* Add spacing between buttons */
    height: 50px;
    color: black;
    font-size: 18px;
    letter-spacing: 1px;
    font-weight: 600;
    border-radius: 15px;
    text-align: center; /* Center text */
    border: none; /* Remove border */
    background-color: #eaeaea; /* Button background */
    transition: background-color 0.3s ease; /* Hover effect */
  }
   /* Navbar Style */
   nav {
            background-color:#aae5e9;
            padding: 15px 30px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 18px;
        }

        nav a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body><form class="sidebar">
<!-- Inside HTML <body> -->

    <nav>
        <div>
            <h1>ADMIN DESK | STUDENT DATABASE</h1>
            <hr>
        </div>

        <a href="newstudentreg.php">
            <input type="button" class="button" value="ADD NEW STUDENT">
        </a>

        
        <input
            class="input"
            placeholder="search by regnumber"
            name="searchStudent"
            onkeydown="if(event.key === 'Enter') { this.form.submit(); }"
        ><label> ||press enter to Show student details </label>
    </nav>
</form>
<?php

// Database connection
include 'connectiondb.php';
// Get the search input
if (isset($_GET['searchStudent'])) {
    $search = $conn->real_escape_string($_GET['searchStudent']);

    // SQL query
    $sql = "SELECT * FROM users WHERE regnumber LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output results

        while($row = $result->fetch_assoc()) {
            echo '<div class="our-team">
                    <h3 class="name">' . htmlspecialchars($row['fullname']) . '</h3><br></br><br></br>
                      <h3 class="name">' . htmlspecialchars($row['regnumber']) . '</h3><br></br><br></br>
                        <h3 class="name">' . htmlspecialchars($row['email']) . '</h3><br></br><br></br>
                    <input type= "button" class= "button" value="update details" style= "   display: horizontal; /* Buttons stack vertically */
                                                                width: 100%; /* Responsive width */
                                                                max-width: 250px; /* Limit button size */
                                                                margin: 10px auto; /* Add spacing between buttons */
                                                                height: 50px;
                                                                color: black;
                                                                font-size: 18px;
                                                                letter-spacing: 1px;
                                                                font-weight: 600;
                                                                border-radius: 25px;
                                                                text-align: center; /* Center text */
                                                                border: none; /* Remove border */
                                                                ">
                    </input>
                    <input type="button" class="button" value="view CV" style= "   display: horizontal; /* Buttons stack vertically */
                                                                width: 100%; /* Responsive width */
                                                                max-width: 250px; /* Limit button size */
                                                                margin: 10px auto; /* Add spacing between buttons */
                                                                height: 50px;
                                                                color: black;
                                                                font-size: 18px;
                                                                letter-spacing: 1px;
                                                                font-weight: 600;
                                                                border-radius: 25px;
                                                                text-align: center; /* Center text */
                                                                border: none; /* Remove border */
                                                                ">
                    </input>
                </div>
                ';
        }
        ;
    } else {
        echo "No results found.";
    }
} else {

    echo
    '
             ';
}

$conn->close();

?>

<div class="container">
        <?php
        // Generate HTML containers dynamically
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '
                <div class="our-team">
                    <h3 class="name">' . htmlspecialchars($row['fullname']) . '</h3><br></br><br></br>
                    <input type= "button" class= "button" value="update details" style= "   display: horizontal; /* Buttons stack vertically */
                                                                width: 100%; /* Responsive width */
                                                                max-width: 250px; /* Limit button size */
                                                                margin: 10px auto; /* Add spacing between buttons */
                                                                height: 50px;
                                                                color: black;
                                                                font-size: 18px;
                                                                letter-spacing: 1px;
                                                                font-weight: 600;
                                                                border-radius: 25px;
                                                                text-align: center; /* Center text */
                                                                border: none; /* Remove border */
                                                                ">
                    </input>
                    <input type="button" class="button" value="view CV" style= "   display: horizontal; /* Buttons stack vertically */
                                                                width: 100%; /* Responsive width */
                                                                max-width: 250px; /* Limit button size */
                                                                margin: 10px auto; /* Add spacing between buttons */
                                                                height: 50px;
                                                                color: black;
                                                                font-size: 18px;
                                                                letter-spacing: 1px;
                                                                font-weight: 600;
                                                                border-radius: 25px;
                                                                text-align: center; /* Center text */
                                                                border: none; /* Remove border */
                                                                ">
                    </input>
                </div>
                ';

            }

        }

         else {
            echo '<div class="our-team"><h3 class="name">Student with those details not found !!</h3></div>';
        }

        ?>



</body>
</html>
