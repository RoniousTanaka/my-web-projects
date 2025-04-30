<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "interndb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$success_message = '';
$error_message = '';
$internship_id = '';
$title = '';
$company = '';
$description = '';
$required_skills = '';
$min_gpa = 6.0;
$location = '';
$industry = '';
$deadline = '';
$is_editing = false;

// Check if the internships table exists, if not create it
$check_table_sql = "SHOW TABLES LIKE 'internships'";
$table_result = $conn->query($check_table_sql);

if ($table_result->num_rows == 0) {
    // Create the internships table
    $create_table_sql = "CREATE TABLE internships (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        company VARCHAR(255) NOT NULL,
        description TEXT,
        required_skills TEXT,
        min_gpa FLOAT DEFAULT 6.0,
        location VARCHAR(255),
        industry VARCHAR(255),
        deadline DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table_sql) === TRUE) {
        $success_message = "Internships table created successfully!";
    } else {
        $error_message = "Error creating table: " . $conn->error;
    }
}

// Handle form submission for adding/editing internship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_internship'])) {
    // Get form data
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $required_skills = filter_input(INPUT_POST, 'required_skills', FILTER_SANITIZE_STRING);
    $min_gpa = filter_input(INPUT_POST, 'min_gpa', FILTER_VALIDATE_FLOAT) ?? 6.0;
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $industry = filter_input(INPUT_POST, 'industry', FILTER_SANITIZE_STRING);
    $deadline = filter_input(INPUT_POST, 'deadline', FILTER_SANITIZE_STRING);
    
    // Validate required fields
    if (empty($title) || empty($company)) {
        $error_message = "Title and Company are required fields.";
    } else {
        // Check if we're editing an existing internship
        if (isset($_POST['internship_id']) && !empty($_POST['internship_id'])) {
            $internship_id = filter_input(INPUT_POST, 'internship_id', FILTER_VALIDATE_INT);
            
            // Update existing internship
            $sql = "UPDATE internships SET 
                title = ?, 
                company = ?, 
                description = ?, 
                required_skills = ?, 
                min_gpa = ?, 
                location = ?, 
                industry = ?, 
                deadline = ?
                WHERE id = ?";
                
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssdsssi", $title, $company, $description, $required_skills, $min_gpa, $location, $industry, $deadline, $internship_id);
            
            if ($stmt->execute()) {
                $success_message = "Internship updated successfully!";
                // Reset form fields
                $title = $company = $description = $required_skills = $location = $industry = $deadline = '';
                $min_gpa = 6.0;
                $is_editing = false;
            } else {
                $error_message = "Error updating internship: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            // Add new internship
            $sql = "INSERT INTO internships (title, company, description, required_skills, min_gpa, location, industry, deadline) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssdss", $title, $company, $description, $required_skills, $min_gpa, $location, $industry, $deadline);
            
            if ($stmt->execute()) {
                $success_message = "Internship added successfully!";
                // Reset form fields
                $title = $company = $description = $required_skills = $location = $industry = $deadline = '';
                $min_gpa = 6.0;
            } else {
                $error_message = "Error adding internship: " . $stmt->error;
            }
            
            $stmt->close();
        }
    }
}

// Handle edit request
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $internship_id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
    
    $sql = "SELECT * FROM internships WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $internship_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $internship = $result->fetch_assoc();
        $title = $internship['title'];
        $company = $internship['company'];
        $description = $internship['description'];
        $required_skills = $internship['required_skills'];
        $min_gpa = $internship['min_gpa'];
        $location = $internship['location'];
        $industry = $internship['industry'];
        $deadline = $internship['deadline'];
        $is_editing = true;
    }
    
    $stmt->close();
}

// Handle delete request
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $internship_id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    
    $sql = "DELETE FROM internships WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $internship_id);
    
    if ($stmt->execute()) {
        $success_message = "Internship deleted successfully!";
    } else {
        $error_message = "Error deleting internship: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch all internships
$sql = "SELECT * FROM internships ORDER BY created_at DESC";
$result = $conn->query($sql);
$internships = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $internships[] = $row;
    }
}
?>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        nav a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
        }
        
        nav a:hover {
            text-decoration: underline;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 200px;
            box-sizing: border-box;
        }
        
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
            background-color: #e74c3c;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-success {
            background-color: #2ecc71;
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .section-title {
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        table tr:hover {
            background-color: #f5f5f5;
        }
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .actions a {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
        }
        
        .edit-btn {
            background-color: #3498db;
        }
        
        .delete-btn {
            background-color: #e74c3c;
        }
        
        @media (max-width: 768px) {
            .form-col {
                flex: 100%;
                margin-bottom: 1rem;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Internship Management System</h1>
            <nav>
                <a href="index.html">Home</a>
                <a href="internships.php">Manage Internships</a>
                <a href="match_candidates.php">Match Candidates</a>
                <a href="cvmaker.php">Update CV</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2 class="section-title"><?php echo $is_editing ? 'Edit Internship' : 'Add New Internship'; ?></h2>
            <form method="POST" action="internships.php">
                <?php if ($is_editing): ?>
                    <input type="hidden" name="internship_id" value="<?php echo htmlspecialchars($internship_id); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="title">Internship Title *</label>
                            <input type="text" id="title" name="title" class="form-control" required 
                                value="<?php echo htmlspecialchars($title); ?>" placeholder="e.g. Software Developer Intern">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="company">Company Name *</label>
                            <input type="text" id="company" name="company" class="form-control" required 
                                value="<?php echo htmlspecialchars($company); ?>" placeholder="e.g. Tech Solutions Ltd">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" 
                        placeholder="Describe the internship position, responsibilities, etc."><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="required_skills">Required Skills (comma separated)</label>
                    <textarea id="required_skills" name="required_skills" class="form-control" rows="3" 
                        placeholder="e.g. Programming, Network Security, Data Analysis"><?php echo htmlspecialchars($required_skills); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="min_gpa">Minimum GPA (0-10)</label>
                            <input type="number" id="min_gpa" name="min_gpa" class="form-control" min="0" max="10" step="0.1" 
                                value="<?php echo htmlspecialchars($min_gpa); ?>">
                            <small>Based on scale: O=10, A+=9, A=8, B+=7, B=6, C=5.5, P=5</small>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" class="form-control" 
                                value="<?php echo htmlspecialchars($location); ?>" placeholder="e.g. Harare">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="industry">Industry</label>
                            <input type="text" id="industry" name="industry" class="form-control" 
                                value="<?php echo htmlspecialchars($industry); ?>" placeholder="e.g. Information Technology">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="deadline">Application Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="form-control" 
                        value="<?php echo htmlspecialchars($deadline); ?>">
                </div>
                
                <div class="form-group">
                    <button type="submit" name="save_internship" class="btn btn-success">
                        <?php echo $is_editing ? 'Update Internship' : 'Add Internship'; ?>
                    </button>
                    <?php if ($is_editing): ?>
                        <a href="internships.php" class="btn">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h2 class="section-title">Existing Internships</h2>
            
            <?php if (empty($internships)): ?>
                <p>No internships have been added yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Min GPA</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($internships as $internship): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($internship['title']); ?></td>
                                <td><?php echo htmlspecialchars($internship['company']); ?></td>
                                <td><?php echo htmlspecialchars($internship['location']); ?></td>
                                <td><?php echo htmlspecialchars($internship['min_gpa']); ?></td>
                                <td><?php echo htmlspecialchars($internship['deadline']); ?></td>
                                <td class="actions">
                                    <a href="internships.php?edit=<?php echo $internship['id']; ?>" class="edit-btn">Edit</a>
                                    <a href="internships.php?delete=<?php echo $internship['id']; ?>" class="delete-btn" 
                                       onclick="return confirm('Are you sure you want to delete this internship?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2 class="section-title">Find Matching Candidates</h2>
            <p>Once you've created internships, you can find matching candidates based on their skills and academic performance.</p>
            <a href="match_candidates.php" class="btn">Match Candidates</a>
        </div>
    </div>
</body>
</html>
