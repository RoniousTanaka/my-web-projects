<?php

// Only process matching if form is submitted or we're showing all matches
$showResults = false;
$customCriteria = false;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "interndb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for custom criteria
$criteriaTitle = "";
$criteriaCompany = "";
$criteriaSkills = "";
$criteriaMinGPA = 6.0;
$criteriaLocation = "";
$criteriaIndustry = "";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_candidates'])) {
    $showResults = true;
    $customCriteria = true;
    
    // Get form data
    $criteriaTitle = filter_input(INPUT_POST, 'position_title', FILTER_SANITIZE_STRING) ?? "";
    $criteriaCompany = filter_input(INPUT_POST, 'company_name', FILTER_SANITIZE_STRING) ?? "";
    $criteriaSkills = filter_input(INPUT_POST, 'required_skills', FILTER_SANITIZE_STRING) ?? "";
    $criteriaMinGPA = filter_input(INPUT_POST, 'min_gpa', FILTER_VALIDATE_FLOAT) ?? 6.0;
    $criteriaLocation = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING) ?? "";
    $criteriaIndustry = filter_input(INPUT_POST, 'industry', FILTER_SANITIZE_STRING) ?? "";
}
// If no form submission but "show all" is requested
elseif (isset($_GET['show_all'])) {
    $showResults = true;
    $customCriteria = false;
}

// Function to calculate average GPA from semester results
function calculateAverageGPA($semesterResults) {
    if (empty($semesterResults)) {
        return 0;
    }
    
    $data = json_decode($semesterResults, true);
    if (!$data || !isset($data['numerical_values'])) {
        return 0;
    }
    
    $numericalValues = $data['numerical_values'];
    $totalPoints = 0;
    $validCourses = 0;
    
    foreach ($numericalValues as $value) {
        if (!empty($value) && $value !== "carry" && is_numeric($value)) {
            $totalPoints += floatval($value);
            $validCourses++;
        }
    }
    
    return $validCourses > 0 ? $totalPoints / $validCourses : 0;
}

// Function to extract skills from CV data
function extractSkills($skills) {
    if (empty($skills)) {
        return [];
    }
    
    // Split by common delimiters and clean up
    $skillsArray = preg_split('/[,;\n]+/', $skills);
    return array_map('trim', $skillsArray);
}

// Function to calculate skill match score
function calculateSkillMatch($studentSkills, $internshipSkills) {
    $studentSkillsArray = extractSkills($studentSkills);
    $internshipSkillsArray = extractSkills($internshipSkills);
    
    if (empty($studentSkillsArray) || empty($internshipSkillsArray)) {
        return 0;
    }
    
    // Calculate how many skills match (case insensitive)
    $matchCount = 0;
    foreach ($studentSkillsArray as $studentSkill) {
        foreach ($internshipSkillsArray as $internshipSkill) {
            if (stripos($studentSkill, $internshipSkill) !== false || 
                stripos($internshipSkill, $studentSkill) !== false) {
                $matchCount++;
                break; // Count each student skill only once
            }
        }
    }
    
    // Return a normalized score (0-100%)
    return count($internshipSkillsArray) > 0 ? 
        ($matchCount / count($internshipSkillsArray)) * 100 : 0;
}

// Function to calculate grade match score
function calculateGradeMatch($studentGPA, $minGPA) {
    if ($studentGPA < $minGPA) {
        return 0; // Below minimum requirement
    }
    
    // Scale: 0-100% where minimum GPA = 50% and max GPA (10) = 100%
    $range = 10 - $minGPA;
    if ($range <= 0) {
        return 100; // If min GPA is 10, any qualifying student gets 100%
    }
    
    $score = (($studentGPA - $minGPA) / $range) * 50 + 50;
    return min(100, max(50, $score)); // Ensure between 50-100%
}

// Only process matching if we're showing results
if ($showResults) {
    // If using custom criteria
    if ($customCriteria) {
        // Create a custom internship object from form data
        $internships_result = [
            [
                'id' => 'custom',
                'title' => $criteriaTitle,
                'company' => $criteriaCompany,
                'required_skills' => $criteriaSkills,
                'min_gpa' => $criteriaMinGPA,
                'location' => $criteriaLocation,
                'industry' => $criteriaIndustry
            ]
        ];
    } else {
        // Fetch all internships from database
        $internships_sql = "SELECT * FROM internships";
        $internships_result = $conn->query($internships_sql);

        // Check if we have internships
        if (!$internships_result || $internships_result->num_rows === 0) {
            echo "<div class='alert alert-info'>No internships are currently available in the system.</div>";
            // For testing, let's create a sample internship
            $internships_result = [
                [
                    'id' => 'sample1',
                    'title' => 'Cybersecurity Intern',
                    'company' => 'TechSecure Solutions',
                    'required_skills' => 'Network Security, Cryptography, Risk Management',
                    'min_gpa' => 7.0,
                    'location' => 'Harare',
                    'industry' => 'Information Security'
                ],
                [
                    'id' => 'sample2',
                    'title' => 'Software Development Intern',
                    'company' => 'CodeCraft Technologies',
                    'required_skills' => 'Programming, Data Structures, Web Technologies',
                    'min_gpa' => 6.0,
                    'location' => 'Bulawayo',
                    'industry' => 'Software Development'
                ]
            ];
        } else {
            $internships_result = $internships_result->fetch_all(MYSQLI_ASSOC);
        }
    }

    // Fetch all students
    $students_sql = "SELECT id, full_name, skills, semester_results, physical_address FROM cv_data1";
    $students_result = $conn->query($students_sql);
$companyname= "SELECT company_name FROM companies";
    if (!$students_result || $students_result->num_rows === 0) {
        echo "<div class='alert alert-warning'>No student CVs found in the system.</div>";
    } else {
        $students = $students_result->fetch_all(MYSQLI_ASSOC);
        
        // Process each internship
        foreach ($internships_result as $internship) {
            $internshipId = $internship['id'] ?? 'custom';
            $internshipTitle = $internship['title'] ?? 'Custom Search';
            $internshipCompany = $internship['company'] ?? '';
            $requiredSkills = $internship['required_skills'] ?? '';
            $minGPA = floatval($internship['min_gpa'] ?? 6.0);
            $location = $internship['location'] ?? '';
            $industry = $internship['industry'] ?? '';
            
            echo "<div class='internship-card'>";
            if ($customCriteria) {
                echo "<h2>Custom Candidate Search</h2>";
            } else {
                echo "<h2>Internship: " . htmlspecialchars($internshipTitle) . " at " . htmlspecialchars($internshipCompany) . "</h2>";
            }
            
            echo "<div class='criteria-summary'>";
            if (!empty($requiredSkills)) {
                echo "<p><strong>Required Skills:</strong> " . htmlspecialchars($requiredSkills) . "</p>";
            }
            echo "<p><strong>Minimum GPA:</strong> " . number_format($minGPA, 1) . "</p>";
            if (!empty($location)) {
                echo "<p><strong>Location:</strong> " . htmlspecialchars($location) . "</p>";
            }
            if (!empty($industry)) {
                echo "<p><strong>Industry:</strong> " . htmlspecialchars($industry) . "</p>";
            }
            echo "</div>";
            
            // Track the best matches for this internship
            $matches = [];
            
            foreach ($students as $student) {
                $studentId = $student['id'];
                $studentName = $student['full_name'];
                $studentSkills = $student['skills'];
                $semesterResults = $student['semester_results'];
                $studentLocation = $student['physical_address'] ?? '';
                
                // Calculate student's GPA
                $studentGPA = calculateAverageGPA($semesterResults);
                
                // Calculate skill match score (0-100%)
                $skillMatchScore = calculateSkillMatch($studentSkills, $requiredSkills);
                
                // Calculate grade match score (0-100%)
                $gradeMatchScore = calculateGradeMatch($studentGPA, $minGPA);
                
                // Calculate location match (simple bonus)
                $locationMatchScore = 0;
                if (!empty($location) && !empty($studentLocation)) {
                    if (stripos($studentLocation, $location) !== false) {
                        $locationMatchScore = 10; // 10% bonus for location match
                    }
                }
                
                // Only consider students who meet minimum GPA
                if ($studentGPA >= $minGPA) {
                    // Calculate total match score (weighted average)
                    $totalMatchScore = ($skillMatchScore * 0.6) + ($gradeMatchScore * 0.3) + $locationMatchScore;
                    
                    $matches[] = [
                        'student_id' => $studentId,
                        'student_name' => $studentName,
                        'gpa' => $studentGPA,
                        'skill_match' => $skillMatchScore,
                        'grade_match' => $gradeMatchScore,
                        'location_match' => $locationMatchScore,
                        'total_score' => $totalMatchScore
                    ];
                }
            }
            
            // Sort matches by total score (descending)
            usort($matches, function($a, $b) {
                return $b['total_score'] <=> $a['total_score'];
            });
            
            // Display top matches
            if (count($matches) > 0) {
                echo "<h3>Top Candidates:</h3>";
                echo "<table class='match-table'>";
                echo "<thead><tr><th>Rank</th><th>Student</th><th>GPA</th><th>Skill Match</th><th>Grade Match</th><th>Total Match</th></tr></thead>";
                echo "<tbody>";
                
                $maxToShow = count($matches); // Show all matches
                for ($i = 0; $i < $maxToShow; $i++) {
                    $match = $matches[$i];
                    echo "<tr>";
                    echo "<td>" . ($i + 1) . "</td>";
                    echo "<td>" . htmlspecialchars($match['student_name']) . "</td>";
                    echo "<td>" . number_format($match['gpa'], 2) . "</td>";
                    echo "<td>" . number_format($match['skill_match'], 1) . "%</td>";
                    echo "<td>" . number_format($match['grade_match'], 1) . "%</td>";
                    echo "<td>" . number_format($match['total_score'], 1) . "%</td>";
                    echo "</tr>";
                }
                
                echo "</tbody></table>";
            } else {
                echo "<p>No suitable candidates found for this criteria.</p>";
            }
            
            echo "</div>";
        }
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
            background-color:hsl(207, 28.20%, 92.40%);
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
        
        .search-form {
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
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        .internship-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            padding: 1.5rem;
        }
        
        .criteria-summary {
            background-color: #f9f9f9;
            border-left: 4px solid #3498db;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .match-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .match-table th, .match-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .match-table th {
            background-color: #f2f2f2;
        }
        
        .match-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .match-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-info {
            background-color: #d9edf7;
            border: 1px solid #bce8f1;
            color: #31708f;
        }
        
        .alert-warning {
            background-color: #fcf8e3;
            border: 1px solid #faebcc;
            color: #8a6d3b;
        }
        
        .section-title {
            border-bottom: 2px solidhsl(203, 82.10%, 89.00%);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .form-col {
                flex: 100%;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        
    </header>

    <div class="container">
        <section class="search-form">
            <h2 class="section-title">Find Matching Candidates</h2>
            <form method="POST" action="match_candidates.php">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="position_title">Position Title</label>
                            <input type="text" id="position_title" name="position_title" class="form-control" 
                                value="<?php echo htmlspecialchars($criteriaTitle); ?>" placeholder="e.g. Web Developer Intern">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="company_name">Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" 
                                value="<?php echo htmlspecialchars($criteriaCompany); ?>" placeholder="e.g. Tech Solutions Ltd">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="required_skills">Required Skills (comma separated)</label>
                    <textarea id="required_skills" name="required_skills" class="form-control" rows="3" 
                        placeholder="e.g. Programming, Network Security, Data Analysis"><?php echo htmlspecialchars($criteriaSkills); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="min_gpa">Minimum GPA (0-10)</label>
                            <input type="number" id="min_gpa" name="min_gpa" class="form-control" min="0" max="10" step="0.1" 
                                value="<?php echo htmlspecialchars($criteriaMinGPA); ?>">
                            <small>Based on scale: O=10, A+=9, A=8, B+=7, B=6, C=5.5, P=5</small>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="location">Preferred Location</label>
                            <input type="text" id="location" name="location" class="form-control" 
                                value="<?php echo htmlspecialchars($criteriaLocation); ?>" placeholder="e.g. Harare">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="industry">Industry</label>
                            <input type="text" id="industry" name="industry" class="form-control" 
                                value="<?php echo htmlspecialchars($criteriaIndustry); ?>" placeholder="e.g. Information Technology">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="search_candidates" class="btn">Find Matching Candidates</button>
                    <a href="match_candidates.php?show_all=1" class="btn btn-secondary">Show All Internship Matches</a>
                </div>
            </form>
        </section>

        <section class="results">
            <?php if (!$showResults): ?>
                <div class="alert alert-info">
                    Enter your criteria above and click "Find Matching Candidates" to see results.
                </div>
            <?php endif; ?>
            <!-- Results are displayed by the PHP code above -->
        </section>
    </div>
</body>
</html>
