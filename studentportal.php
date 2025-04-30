<?php

include 'header.php'; 

?>


    <style>
        /* Body and layout styles */
        body {
            background: #e6edf0 no-repeat center center fixed;
            background-size: cover;
            color: #333; 
        }

        /* Card and sidebar styles */
        .card {
            background: #e6edf0;
            border: none;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Sidebar styling */
        .sidebar {
        position: fixed;
        top: 70px;
        left: 0;
        width: 250px;
        height: flex-direction;
        background-color: #4F9D9C;
        padding: 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        border-radius: 0 15px 15px 0;
}

user agent stylesheet
aside {
    display: block;
    unicode-bidi: isolate;
}

        .sidebar .button {
            display: block;
            width: 100%;
            margin: 10px auto;
            height: 50px;
            color: black;
            font-size: 18px;
            letter-spacing: 1px;
            font-weight: 600;
            border-radius: 25px;
            text-align: center;
            border: none;
            background-color: #eaeaea;
            transition: background-color 0.3s ease;
        }

        .sidebar .button:hover {
            background-color: #ccc;
            cursor: pointer;
        }

        /* Content frame for iframe */
        .content-frame {
    margin-left: 18%;
    width: 82%;
    height: 100vh;
    border-radius: 15px;
    overflow: auto;
    padding: 20px;
    background: #ffffff;
}


        /* Profile Image styling */
        .profile-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-section img {
            border-radius: 50%;
            max-width: 150px;
            margin-bottom: 10px;
        }

        .profile-section h1 {
            margin-bottom: 20px;
        }

    </style>
</head>

<body>


    <!-- Content frame for loading pages -->
    <div id="content-container" class="content-frame">
    <!-- Loaded content will appear here -->
</div>

    <script>

    function openPage(page) {
        fetch(page)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Page not found or error loading page");
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('content-container').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('content-container').innerHTML = "<p>Error loading page: " + error.message + "</p>";
            });
    }
</script>

</body>
</html>
