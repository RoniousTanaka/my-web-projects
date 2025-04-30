<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "interndb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

// Fetch all internships
$internships_sql = "SELECT * FROM internships";
$internships_result = $conn->query($internships_sql);

// Check if we have internships
if (!$internships_result || $internships_result->num_rows === 0) {
    echo "<div class='alert alert-info'>No internships are currently available in the system.</div>";
    // For testing, let's create a sample internship
    echo "<h3>Sample Internship Data (For Testing)</h3>";
    $sampleInternships = [
        [
            'id' => 'sample1',
            'title' => 'Cybersecurity Intern',
            'company' => 'TechSecure Solutions',
            'required_skills' => 'Network Security, Cryptography, Risk Management',
            'min_gpa' => 7.0
        ],
        [
            'id' => 'sample2',
            'title' => 'Software Development Intern',
            'company' => 'CodeCraft Technologies',
            'required_skills' => 'Programming, Data Structures, Web Technologies',
            'min_gpa' => 6.0
        ]
    ];
    
    $internships_result = $sampleInternships;
} else {
    $internships_result = $internships_result->fetch_all(MYSQLI_ASSOC);
}

// Fetch all students
$students_sql = "SELECT id, full_name, skills, semester_results FROM cv_data1";
$students_result = $conn->query($students_sql);

if (!$students_result || $students_result->num_rows === 0) {
    echo "<div class='alert alert-warning'>No student CVs found in the system.</div>";
} else {
    $students = $students_result->fetch_all(MYSQLI_ASSOC);
    
    // Process each internship
    foreach ($internships_result as $internship) {
        $internshipId = $internship['id'] ?? 'sample';
        $internshipTitle = $internship['title'] ?? 'Sample Internship';
        $internshipCompany = $internship['company'] ?? 'Sample Company';
        $requiredSkills = $internship['required_skills'] ?? '';
        $minGPA = floatval($internship['min_gpa'] ?? 6.0);
        
        echo "<div class='internship-card'>";
        echo "<h2>Internship: " . htmlspecialchars($internshipTitle) . " at " . htmlspecialchars($internshipCompany) . "</h2>";
        echo "<p><strong>Required Skills:</strong> " . htmlspecialchars($requiredSkills) . "</p>";
        echo "<p><strong>Minimum GPA:</strong> " . number_format($minGPA, 1) . "</p>";
        
        // Track the best matches for this internship
        $matches = [];
        
        foreach ($students as $student) {
            $studentId = $student['id'];
            $studentName = $student['full_name'];
            $studentSkills = $student['skills'];
            $semesterResults = $student['semester_results'];
            
            // Calculate student's GPA
            $studentGPA = calculateAverageGPA($semesterResults);
            
            // Calculate skill match score (0-100%)
            $skillMatchScore = calculateSkillMatch($studentSkills, $requiredSkills);
            
            // Calculate grade match score (0-100%)
            $gradeMatchScore = calculateGradeMatch($studentGPA, $minGPA);
            
            // Only consider students who meet minimum GPA
            if ($studentGPA >= $minGPA) {
                // Calculate total match score (weighted average)
                $totalMatchScore = ($skillMatchScore * 0.6) + ($gradeMatchScore * 0.4);
                
                $matches[] = [
                    'student_id' => $studentId,
                    'student_name' => $studentName,
                    'gpa' => $studentGPA,
                    'skill_match' => $skillMatchScore,
                    'grade_match' => $gradeMatchScore,
                    'total_score' => $totalMatchScore
                ];
            }
        }
        
        // Sort matches by total score (descending)
        usort($matches, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });
        
        // Display top 5 matches
        if (count($matches) > 0) {
            echo "<h3>Top Candidates:</h3>";
            echo "<table class='match-table'>";
            echo "<thead><tr><th>Rank</th><th>Student</th><th>GPA</th><th>Skill Match</th><th>Grade Match</th><th>Overall Match</th></tr></thead>";
            echo "<tbody>";
            
            $maxToShow = min(5, count($matches));
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
            echo "<p>No suitable candidates found for this internship.</p>";
        }
        
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Matching</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
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
        
        .internship-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            padding: 1.5rem;
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
    </style>
</head>
<body>
  

    <section class="internships">
        <h2>Internship Matching Results</h2>
        <!-- Results are displayed above -->
    </section>
</body>
</html>
