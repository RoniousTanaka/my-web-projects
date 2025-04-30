<?php
session_start();
include "studentname.php";

// Database configuration
$host = 'localhost';
$dbname = 'interndb';
$username = 'root';
$password = '';

// Create PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $linkedin = filter_input(INPUT_POST, 'linkedin', FILTER_SANITIZE_URL);
    $physical_address = filter_input(INPUT_POST, 'physical_address', FILTER_SANITIZE_STRING);
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
    $personal_statement = filter_input(INPUT_POST, 'PersonalStatement', FILTER_SANITIZE_STRING);
    $university = filter_input(INPUT_POST, 'university', FILTER_SANITIZE_STRING);
    $program = filter_input(INPUT_POST, 'program', FILTER_SANITIZE_STRING);
    $expected_graduation = filter_input(INPUT_POST, 'expected_graduation', FILTER_SANITIZE_STRING);
    $certifications = filter_input(INPUT_POST, 'certifications', FILTER_SANITIZE_STRING);
    $skills = filter_input(INPUT_POST, 'skills', FILTER_SANITIZE_STRING);
    $interests = filter_input(INPUT_POST, 'interests', FILTER_SANITIZE_STRING);
    
    // Process semester results
    $grades = $_POST['grades'] ?? [];
    $statuses = $_POST['statuses'] ?? [];
    $numerical_values = $_POST['numerical_values'] ?? []; // Add numerical values array
    $semester_results = json_encode(['grades' => $grades, 'statuses' => $statuses, 'numerical_values' => $numerical_values]); // Include numerical values

    // Check if this is an update or new entry
    $sql_check = "SELECT id FROM cv_data1 WHERE full_name = :full_name LIMIT 1";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':full_name', $full_name);
    $stmt_check->execute();
    $existing_record = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existing_record) {
        // Update existing record
        $sql = "UPDATE cv_data1 SET 
            email = :email,
            linkedin = :linkedin,
            physical_address = :physical_address,
            phone_number = :phone_number,
            personal_statement = :personal_statement,
            university = :university,
            program = :program,
            expected_graduation = :expected_graduation,
            certifications = :certifications,
            skills = :skills,
            interests = :interests,
            semester_results = :semester_results,
            updated_at = NOW()
            WHERE full_name = :full_name";
    } else {
        // Insert new record
        $sql = "INSERT INTO cv_data1 (
            full_name, email, linkedin, physical_address, phone_number, personal_statement,
            university, program, expected_graduation, certifications, skills, interests, semester_results
        ) VALUES (
            :full_name, :email, :linkedin, :physical_address, :phone_number, :personal_statement,
            :university, :program, :expected_graduation, :certifications, :skills, :interests, :semester_results
        )";
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':linkedin', $linkedin);
        $stmt->bindParam(':physical_address', $physical_address);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':personal_statement', $personal_statement);
        $stmt->bindParam(':university', $university);
        $stmt->bindParam(':program', $program);
        $stmt->bindParam(':expected_graduation', $expected_graduation);
        $stmt->bindParam(':certifications', $certifications);
        $stmt->bindParam(':skills', $skills);
        $stmt->bindParam(':interests', $interests);
        $stmt->bindParam(':semester_results', $semester_results);

        if ($stmt->execute()) {
            $success_message = "CV data successfully " . ($existing_record ? "updated" : "added") . "!";
            
            // Load the updated data for display
            $sql_load = "SELECT * FROM cv_data1 WHERE full_name = :full_name LIMIT 1";
            $stmt_load = $pdo->prepare($sql_load);
            $stmt_load->bindParam(':full_name', $full_name);
            $stmt_load->execute();
            $cv_data = $stmt_load->fetch(PDO::FETCH_ASSOC);
            
            if ($cv_data) {
                // Decode semester results
                $semester_data = json_decode($cv_data['semester_results'], true);
                $grades = $semester_data['grades'] ?? [];
                $statuses = $semester_data['statuses'] ?? [];
                $numerical_values = $semester_data['numerical_values'] ?? []; // Get numerical values
            }
        } else {
            $error_message = "There was an error processing the CV data.";
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}

// Load existing data if not submitting
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $sql_load = "SELECT * FROM cv_data1 WHERE full_name = :full_name LIMIT 1";
    $stmt_load = $pdo->prepare($sql_load);
    $stmt_load->bindParam(':full_name', $studentName);
    $stmt_load->execute();
    $cv_data = $stmt_load->fetch(PDO::FETCH_ASSOC);
    
    if ($cv_data) {
        // Decode semester results
        $semester_data = json_decode($cv_data['semester_results'], true);
        $grades = $semester_data['grades'] ?? [];
        $statuses = $semester_data['statuses'] ?? [];
        $numerical_values = $semester_data['numerical_values'] ?? []; // Get numerical values
        
        // Set variables for form fields
        $full_name = $cv_data['full_name'];
        $email = $cv_data['email'];
        $linkedin = $cv_data['linkedin'];
        $physical_address = $cv_data['physical_address'];
        $phone_number = $cv_data['phone_number'];
        $personal_statement = $cv_data['personal_statement'];
        $university = $cv_data['university'];
        $program = $cv_data['program'];
        $expected_graduation = $cv_data['expected_graduation'];
        $certifications = $cv_data['certifications'];
        $skills = $cv_data['skills'];
        $interests = $cv_data['interests'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Maker for <?php echo htmlspecialchars($studentName); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
        }
        
        .input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
            resize: vertical;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <h2>Curriculum Vitae for <?php echo htmlspecialchars($studentName); ?></h2>
        <form action="cvmaker.php" method="POST" id="cv-form">
            <!-- Personal Details -->
            <h3>PERSONAL DETAILS</h3>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($full_name ?? $studentName); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="linkedin">LinkedIn Profile URL</label>
                <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/yourprofile" value="<?php echo htmlspecialchars($linkedin ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="physical_address">Physical Address</label>
                <input type="text" id="physical_address" name="physical_address" required value="<?php echo htmlspecialchars($physical_address ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" required value="<?php echo htmlspecialchars($phone_number ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="PersonalStatement">Personal Statement</label>
                <textarea id="PersonalStatement" name="PersonalStatement" required><?php echo htmlspecialchars($personal_statement ?? ''); ?></textarea>
            </div>

            <!-- Educational Background -->
            <h3>EDUCATIONAL QUALIFICATIONS</h3>
            <div class="form-group">
                <label for="university">University</label>
                <input type="text" id="university" name="university" value="<?php echo htmlspecialchars($university ?? 'Harare Institute of Technology'); ?>" required>
            </div>
            <div class="form-group">
                <label for="program">Program of Study</label>
                <input type="text" id="program" name="program" value="Bachelor of Technology (Hons) in Information Security & Assurance" readonly>
            </div>
            <div class="form-group">
                <label for="expected_graduation">Expected Graduation Date</label>
                <input type="date" id="expected_graduation" name="expected_graduation" value="<?php echo htmlspecialchars($expected_graduation ?? '2027-12-31'); ?>" required>
            </div>

            <!-- Semester Results -->
            <div class="form-group">
                <h4>📘 Semester Results (Edit Grade only)</h4>
                <div class="grade-legend" style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                    <strong>Grade Scale:</strong> O=10, A+=9, A=8, B+=7, B=6, C=5.5, P=5, F=carry
                </div>
                <?php
                $semesters = [
                    "ACADEMIC YEAR 2024/25 - Part 2, Semester 2 (Pending)" => [
                        "Techoprenuership IV",
                        "Network Security",
                        "Systems Administration and Security",
                        "Forensics and Incident Response",
                        "Information Systems Risk Management",
                        "Applied Statistics"
                    ],
                    "ACADEMIC YEAR 2024/25 - Part 2, Semester 1" => [
                        "Technoprenuership III",
                        "Secure Coding",
                        "Number Theory",
                        "Data Communications and Networks",
                        "Cryptography and Security",
                        "Secure Software Management"
                    ],
                    "ACADEMIC YEAR (Unspecified) - Part 1, Semester 2" => [
                        "Ethics and Professionalism",
                        "Technoprenuership II",
                        "Visual Programming Concepts",
                        "Database Design and Security",
                        "Data Structures and Algorithms",
                        "Cyberspace Ethics and Laws",
                        "Web Technologies"
                    ],
                    "ACADEMIC YEAR 2023/2024 - Part 1, Semester 1" => [
                        "Technical Communication Skills for Information Sciences",
                        "Technoprenuership I",
                        "Principles of Programming",
                        "Operating Systems",
                        "Introduction to Information Security",
                        "Discrete Mathematics",
                        "Computer Architecture and Design"
                    ]
                ];

                $counter = 0;
                foreach ($semesters as $semesterTitle => $courses): ?>
                    <h3 style="margin-top: 30px;"><?php echo htmlspecialchars($semesterTitle); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course); ?></td>
                                    <td>
                                        <input type="text" name="grades[<?php echo $counter; ?>]" 
                                            placeholder="e.g. A+" 
                                            value="<?php echo htmlspecialchars($grades[$counter] ?? ''); ?>"
                                            oninput="updateGradeInfo(this, <?php echo $counter; ?>)" 
                                            style="width: 100%; padding: 6px;" />
                                    </td>
                                    <td>
                                        <input type="text" name="statuses[<?php echo $counter; ?>]" 
                                            id="status_<?php echo $counter; ?>" 
                                            value="<?php echo htmlspecialchars($statuses[$counter] ?? ''); ?>"
                                            readonly 
                                            style="width: 100%; padding: 6px; background-color: #eee;" />
                                    </td>
                                    <td>
                                        <input type="text" name="numerical_values[<?php echo $counter; ?>]" 
                                            id="numerical_value_<?php echo $counter; ?>" 
                                            value="<?php echo htmlspecialchars($numerical_values[$counter] ?? ''); ?>"
                                            readonly 
                                            style="width: 100%; padding: 6px; background-color: #eee;" />
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>

            <!-- Certifications -->
            <div class="form-group">
                <label for="certifications">Certifications</label>
                <textarea id="certifications" name="certifications" placeholder="List your certifications here e.g., CompTIA Security+, CEH, etc...." required><?php echo htmlspecialchars($certifications ?? ''); ?></textarea>
            </div>

            <!-- Professional Skills -->
            <h3>PROFESSIONAL SKILLS</h3>
            <div class="form-group">
                <label for="skills">Technical Skills</label>
                <textarea id="skills" name="skills" placeholder="List your skills here..." required><?php echo htmlspecialchars($skills ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="interests">Areas of Interest</label>
                <textarea id="interests" name="interests" placeholder="Describe your interests here e.g., Cybersecurity, Data Privacy, Risk Management, etc...." required><?php echo htmlspecialchars($interests ?? ''); ?></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" class="button" value="<?php echo isset($cv_data) ? 'Update Your CV' : 'Create Your CV'; ?>">
            </div>
        </form>

        <!-- Download Buttons -->
        <div style="margin-top: 30px;">
            <button type="button" class="button" onclick="downloadPDF()">📄 Download CV as PDF</button>
            <button type="button" class="button" onclick="downloadWord()">📝 Download CV as Word</button>
        </div>

        <!-- CV Preview Container -->
        <div id="cv-container">
            <div class="cv-header">
                <h1 id="cv-name"><?php echo htmlspecialchars($full_name ?? $studentName); ?></h1>
                <p id="cv-email">Email: <?php echo htmlspecialchars($email ?? ''); ?></p>
                <p id="cv-phone">Phone: <?php echo htmlspecialchars($phone_number ?? ''); ?></p>
                <p id="cv-linkedin">LinkedIn: <?php echo htmlspecialchars($linkedin ?? ''); ?></p>
                <p id="cv-address">Address: <?php echo htmlspecialchars($physical_address ?? ''); ?></p>
            </div>

            <div class="cv-section">
                <h3>Personal Statement</h3>
                <p id="cv-statement"><?php echo nl2br(htmlspecialchars($personal_statement ?? '')); ?></p>
            </div>

            <div class="cv-section">
                <h3>Education</h3>
                <p><strong>University:</strong> <span id="cv-university"><?php echo htmlspecialchars($university ?? 'Harare Institute of Technology'); ?></span></p>
                <p><strong>Program:</strong> <span id="cv-program">Bachelor of Technology (Hons) in Information Security & Assurance</span></p>
                <p><strong>Expected Graduation:</strong> <span id="cv-graduation"><?php echo htmlspecialchars($expected_graduation ?? 'December 2027'); ?></span></p>
            </div>

            <div class="cv-section">
                <h3>Academic Results</h3>
                <?php 
                $counter = 0;
                foreach ($semesters as $semesterTitle => $courses): ?>
                    <h4><?php echo htmlspecialchars($semesterTitle); ?></h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody id="cv-results-<?php echo preg_replace('/[^a-z0-9]/', '-', strtolower($semesterTitle)); ?>">
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course); ?></td>
                                    <td><?php echo htmlspecialchars($grades[$counter] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($statuses[$counter] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($numerical_values[$counter] ?? ''); ?></td>
                                </tr>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>

            <div class="cv-section">
                <h3>Certifications</h3>
                <div id="cv-certifications"><?php echo nl2br(htmlspecialchars($certifications ?? '')); ?></div>
            </div>

            <div class="cv-section">
                <h3>Skills</h3>
                <div id="cv-skills"><?php echo nl2br(htmlspecialchars($skills ?? '')); ?></div>
            </div>

            <div class="cv-section">
                <h3>Interests</h3>
                <div id="cv-interests"><?php echo nl2br(htmlspecialchars($interests ?? '')); ?></div>
            </div>
        </div>
    </div>

    <!-- Load html2pdf.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>
        // Update status and numerical value based on grade input
        function updateGradeInfo(input, index) {
            const grade = input.value.trim().toUpperCase();
            let status = "";
            let numericalValue = "";

            // Set numerical value based on grade
            switch (grade) {
                case "O":
                    numericalValue = "10";
                    status = "Distinction";
                    break;
                case "A+":
                    numericalValue = "9";
                    status = "Distinction";
                    break;
                case "A":
                    numericalValue = "8";
                    status = "Distinction";
                    break;
                case "B+":
                    numericalValue = "7";
                    status = "Merit";
                    break;
                case "B":
                    numericalValue = "6";
                    status = "Merit";
                    break;
                case "C":
                    numericalValue = "5.5";
                    status = "Pass";
                    break;
                case "P":
                    numericalValue = "5";
                    status = "Pass";
                    break;
                case "F":
                    numericalValue = "carry";
                    status = "Fail";
                    break;
                default:
                    numericalValue = grade ? "" : "";
                    status = grade ? "Pending" : "";
            }

            document.getElementById("status_" + index).value = status;
            document.getElementById("numerical_value_" + index).value = numericalValue;
            
            // Update CV preview
            updateCVPreview();
        }

        // Update CV preview as user types
        document.getElementById('cv-form').addEventListener('input', function() {
            updateCVPreview();
        });

        function updateCVPreview() {
            // Personal details
            document.getElementById('cv-name').textContent = document.getElementById('full_name').value || '<?php echo htmlspecialchars($studentName); ?>';
            document.getElementById('cv-email').textContent = 'Email: ' + (document.getElementById('email').value || '');
            document.getElementById('cv-phone').textContent = 'Phone: ' + (document.getElementById('phone_number').value || '');
            document.getElementById('cv-linkedin').textContent = 'LinkedIn: ' + (document.getElementById('linkedin').value || '');
            document.getElementById('cv-address').textContent = 'Address: ' + (document.getElementById('physical_address').value || '');
            
            // Personal statement
            document.getElementById('cv-statement').textContent = document.getElementById('PersonalStatement').value || '';
            
            // Education
            document.getElementById('cv-university').textContent = document.getElementById('university').value || 'Harare Institute of Technology';
            document.getElementById('cv-program').textContent = document.getElementById('program').value || 'Bachelor of Technology (Hons) in Information Security & Assurance';
            document.getElementById('cv-graduation').textContent = document.getElementById('expected_graduation').value || 'December 2027';
            
            // Certifications
            document.getElementById('cv-certifications').innerHTML = document.getElementById('certifications').value 
                ? '<p>' + document.getElementById('certifications').value.replace(/\n/g, '<br>') + '</p>' 
                : '';
            
            // Skills
            document.getElementById('cv-skills').innerHTML = document.getElementById('skills').value 
                ? '<p>' + document.getElementById('skills').value.replace(/\n/g, '<br>') + '</p>' 
                : '';
            
            // Interests
            document.getElementById('cv-interests').innerHTML = document.getElementById('interests').value 
                ? '<p>' + document.getElementById('interests').value.replace(/\n/g, '<br>') + '</p>' 
                : '';
            
            // Update grades in preview
            const gradeInputs = document.querySelectorAll('input[name^="grades["]');
            gradeInputs.forEach(input => {
                const index = input.name.match(/\[(\d+)\]/)[1];
                const row = input.closest('tr');
                if (row) {
                    const previewRow = document.querySelector(`#cv-container tbody tr:nth-child(${row.rowIndex})`);
                    if (previewRow) {
                        previewRow.cells[1].textContent = input.value;
                        previewRow.cells[2].textContent = document.getElementById(`status_${index}`).value;
                        previewRow.cells[3].textContent = document.getElementById(`numerical_value_${index}`).value;
                    }
                }
            });
        }

        // Calculate GPA function
        function calculateGPA() {
            const numericalInputs = document.querySelectorAll('input[name^="numerical_values["]');
            let totalPoints = 0;
            let validCourses = 0;
            
            numericalInputs.forEach(input => {
                const value = input.value.trim();
                if (value && value !== "carry" && !isNaN(parseFloat(value))) {
                    totalPoints += parseFloat(value);
                    validCourses++;
                }
            });
            
            return validCourses > 0 ? (totalPoints / validCourses).toFixed(2) : "N/A";
        }

        // Download functions
        function downloadPDF() {
            const element = document.getElementById('cv-container');
            const opt = {
                margin: 10,
                filename: 'CV_<?php echo $studentName; ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(opt).from(element).save();
        }

        function downloadWord() {
            const header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                "xmlns='http://www.w3.org/TR/REC-html40'>" +
                "<head><meta charset='utf-8'><title>CV</title></head><body>";
            const footer = "</body></html>";
            const content = document.getElementById("cv-container").innerHTML;

            const sourceHTML = header + content + footer;
            const source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
            
            const fileDownload = document.createElement("a");
            document.body.appendChild(fileDownload);
            fileDownload.href = source;
            fileDownload.download = 'CV_<?php echo $studentName; ?>.doc';
            fileDownload.click();
            document.body.removeChild(fileDownload);
        }

        // Initialize CV preview and update any existing grades
        document.addEventListener('DOMContentLoaded', function() {
            const gradeInputs = document.querySelectorAll('input[name^="grades["]');
            gradeInputs.forEach(input => {
                const index = input.name.match(/\[(\d+)\]/)[1];
                if (input.value) {
                    updateGradeInfo(input, index);
                }
            });
            
            updateCVPreview();
        });
    
        // Add GPA display to the CV
        function updateGPADisplay() {
            const gpa = calculateGPA();
            
            // Check if GPA section already exists, if not create it
            let gpaSection = document.querySelector('.cv-gpa-section');
            if (!gpaSection) {
                gpaSection = document.createElement('div');
                gpaSection.className = 'cv-section cv-gpa-section';
                gpaSection.innerHTML = '<h3>Grade Point Average (GPA)</h3><p id="cv-gpa"></p>';
                
                // Insert before certifications section
                const certSection = document.querySelector('.cv-section:nth-of-type(5)');
                if (certSection) {
                    certSection.parentNode.insertBefore(gpaSection, certSection);
                } else {
                    document.getElementById('cv-container').appendChild(gpaSection);
                }
            }
            
            // Update GPA value
            document.getElementById('cv-gpa').textContent = `Current GPA: ${gpa}`;
        }
        
        // Add GPA calculation to the updateCVPreview function
        const originalUpdateCVPreview = updateCVPreview;
        updateCVPreview = function() {
            originalUpdateCVPreview();
            updateGPADisplay();
        };
    </script>
</body>
</html>
