<?php 
session_start();
include 'studentname.php';
include 'connectiondb.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize variables
$errors = [];
$full_name = $email = $phone_number = $skills = $interests = $certifications = $profile_picture = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    // Validate and sanitize input
    $full_name = trim($_POST['full_name'] ?? '');
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    $phone_number = trim($_POST['phone_number'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $interests = trim($_POST['interests'] ?? '');
    $certifications = trim($_POST['certifications'] ?? '');

    // Handle file upload
    if (!empty($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Validate file
        $max_size = 2 * 1024 * 1024; // 2MB
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
        
        if ($_FILES['profile_picture']['size'] > $max_size) {
            $errors[] = "Image size must be less than 2MB";
        } elseif (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF images are allowed";
        } else {
            $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
            $upload_dir = 'uploads/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $destination = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                $profile_picture = $destination;
            } else {
                $errors[] = "Error uploading image";
            }
        }
    }

    // Only proceed if no errors
    if (empty($errors)) {
        // Prepare SQL using prepared statements
        $stmt = $conn->prepare("INSERT INTO profiles 
            (fullname, email, phone_number, skills, interests, certifications, profile_picture) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sssssss", $full_name, $email, $phone_number, $skills, $interests, $certifications, $profile_picture);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Profile created successfully!";
                header("Location: profiles.php");
                exit;
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #profileSection {
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        #profileSection .main-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        #profileSection .content-frame {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="profil sections">
                <div id="edit-profile" class="container main-body" style="margin-top: 20px;">
                    <div class="row gutters-sm">
                        <!-- Profile Image + Name + Bio -->
                        <div class="col-md-4 mb-3">
                            <div class="card p-3">
                                <div class="profile-section">
                                    <img id="profileImage" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User" class="img-fluid">
                                    <button type="button" class="btn btn-primary image-upload-btn" id="uploadImageBtn">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                    <input type="file" name="profile_picture" id="imageUpload" style="display:none;" accept="image/*">
                                    <h4 id="userName">ISA Student</h4>
                                    <textarea name="bio" id="bio" class="form-control" rows="3" placeholder="Add a short bio about yourself..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Form -->
                        <div class="col-md-8">
                            <div class="card mb-3 p-3">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" id="fullName" value="<?php echo htmlspecialchars($studentName); ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone_number" id="phone" class="form-control" placeholder="Enter your phone number">
                                </div>
                                <div class="form-group">
                                    <label>Skills</label>
                                    <textarea name="skills" id="skills" class="form-control" placeholder="List your skills"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Interests</label>
                                    <textarea name="interests" id="interests" class="form-control" placeholder="List your interests"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Certifications</label>
                                    <textarea name="certifications" id="certifications" class="form-control" placeholder="List your certifications"></textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg">Create Profile</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Upload Image
            $('#uploadImageBtn').click(function() {
                $('#imageUpload').click();
            });
            
            $('#imageUpload').change(function (e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profileImage').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>