<?php
// Start session at the very beginning
session_start();

// Enable error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db.php'; // Use require_once for critical includes

// Verify user is logged in
if (!isset($_SESSION['regnumber'])) {
    header("Location: login.php");
    exit();
}

// Close the database connection at the end of processing
register_shutdown_function(function() {
    global $conn;
    if (isset($conn)) {
        mysqli_close($conn);
    }
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Internship Portal">
    <title>Internship Portal</title>
    <style>
        /* CSS Variables for easy theming */
        :root {
            --primary-color: #4F9D9C;
            --secondary-color: #ffdd9b;
            --danger-color: #e74c3c;
            --text-color: #333;
            --bg-color: #f1f8ff;
            --card-bg: #ffffff;
            --shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            --border-radius: 15px;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: var(--bg-color);
            margin: 0;
            padding: 0;
            color: var(--text-color);
            line-height: 1.6;
        }

        .button {
            background-color: var(--secondary-color);
            border: none;
            border-radius: 25px;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #ffca6b;
        }

        main {
            margin-left: 270px;
            padding: 20px;
        }

        #contentArea {
            background: var(--card-bg);
            padding: 20px;
            border-radius: var(--border-radius);
            min-height: 80vh;
            box-shadow: var(--shadow);
        }

        /* Navbar Styles */
        nav {
            background-color: var(--primary-color);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        nav h1 {
            margin: 0;
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }

        .nav-link.logout {
            background-color: var(--danger-color);
        }

        .nav-link.logout:hover {
            background-color: #c0392b;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                margin-left: 0;
                padding: 10px;
            }

            nav {
                flex-direction: column;
                padding: 10px;
            }

            nav a {
                margin: 5px 0;
                width: 100%;
                text-align: center;
            }

            #contentArea {
                min-height: 500px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="match_candidates.php" class="nav-link">Matching</a>
        <a href="post_internship.php" class="nav-link">Post Internship</a>
        <a href="view_applicants.php" class="nav-link">View Applications</a>
        <a href="interview_schedule.php" class="nav-link">Interview Scheduling</a>
        <a href="logout.php" class="nav-link logout">Logout</a>
    </nav>

    <main>
        <div id="contentArea">Loading...</div>
    </main>

    <script>
        // Enhanced page loading with error handling and loading states
        document.addEventListener('DOMContentLoaded', function() {
            // Load default page
            loadPage('dashboard.php');
            
            // Add click handlers for nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                if (!link.classList.contains('logout')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        loadPage(this.getAttribute('href'));
                        // Update active state
                        document.querySelectorAll('.nav-link').forEach(navLink => {
                            navLink.classList.remove('active');
                        });
                        this.classList.add('active');
                    });
                }
            });
        });

        async function loadPage(url) {
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = '<div class="loading">Loading...</div>';
            
            try {
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.text();
                contentArea.innerHTML = data;
                
                // Update browser history without page reload
                history.pushState(null, '', url);
                
            } catch (error) {
                contentArea.innerHTML = `
                    <div class="error-message">
                        <h3>Error loading page</h3>
                        <p>${error.message}</p>
                        <button onclick="loadPage('dashboard.php')">Return to Dashboard</button>
                    </div>
                `;
                console.error('Error loading page:', error);
            }
        }
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            loadPage(window.location.pathname);
        });
    </script>
</body>
</html>