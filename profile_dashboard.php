<?php
session_start();
include 'connectiondb.php';
include 'studentname.php';

// Check if user is viewing or editing a profile
$profile_id = $_GET['id'] ?? null;
$edit_mode = isset($_GET['edit']);

// Fetch profile data if viewing/editing
$profile_data = null;
if ($profile_id) {
    $stmt = $conn->prepare("SELECT * FROM profiles WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile_data = $result->fetch_assoc();
    $stmt->close();
    
    if (!$profile_data) {
        die("Profile not found");
    }
}

// Handle form submission for edits
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // CSRF and validation checks would go here (similar to previous example)
    // Process the form and redirect back to view mode
    header("Location: profiles.php?id=".$profile_id);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $profile_data ? 'Profile' : 'Create Profile'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
        .profile-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .skill-meter {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .skill-progress {
            height: 100%;
            border-radius: 5px;
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if ($profile_data && !$edit_mode): ?>
            <!-- VIEW PROFILE MODE -->
            <div class="profile-header text-center">
                <img src="<?php echo htmlspecialchars($profile_data['profile_picture'] ?: 'https://bootdey.com/img/Content/avatar/avatar7.png'); ?>" 
                     alt="Profile Image" class="profile-image mb-3">
                <h2><?php echo htmlspecialchars($profile_data['fullname']); ?></h2>
                <p class="text-muted"><?php echo htmlspecialchars($profile_data['email']); ?></p>
                
                <?php if ($profile_data['id'] == $_SESSION['user_id'] ?? null): ?>
                    <a href="profiles.php?id=<?php echo $profile_id; ?>&edit=1" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="profile-section">
                        <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile_data['phone_number'] ?? 'Not provided'); ?></p>
                        <p><strong>Skills:</strong> <?php echo htmlspecialchars($profile_data['skills'] ?? 'Not provided'); ?></p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="profile-section">
                        <h4><i class="fas fa-certificate"></i> Certifications</h4>
                        <p><?php echo nl2br(htmlspecialchars($profile_data['certifications'] ?? 'No certifications added')); ?></p>
                    </div>
                    
                    <div class="profile-section">
                        <h4><i class="fas fa-heart"></i> Interests</h4>
                        <p><?php echo nl2br(htmlspecialchars($profile_data['interests'] ?? 'No interests added')); ?></p>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- EDIT/CREATE PROFILE MODE -->
            <form action="<?php echo $profile_data ? 'profiles.php?id='.$profile_id : 'save_profile.php'; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="profile-header text-center">
                    <img id="profileImagePreview" 
                         src="<?php echo htmlspecialchars($profile_data['profile_picture'] ?? 'https://bootdey.com/img/Content/avatar/avatar7.png'); ?>" 
                         alt="Profile Image" class="profile-image mb-3">
                    <div class="mb-3">
                        <input type="file" name="profile_picture" id="profilePictureUpload" accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('profilePictureUpload').click()">
                            <i class="fas fa-camera"></i> Change Photo
                        </button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="profile-section">
                            <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                            
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="full_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($profile_data['fullname'] ?? $studentName); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($profile_data['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" 
                                       value="<?php echo htmlspecialchars($profile_data['phone_number'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="profile-section">
                            <h4><i class="fas fa-certificate"></i> Certifications</h4>
                            <textarea name="certifications" class="form-control" rows="4"><?php 
                                echo htmlspecialchars($profile_data['certifications'] ?? ''); 
                            ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="profile-section">
                            <h4><i class="fas fa-code"></i> Skills</h4>
                            <textarea name="skills" class="form-control" rows="4"><?php 
                                echo htmlspecialchars($profile_data['skills'] ?? ''); 
                            ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="profile-section">
                            <h4><i class="fas fa-heart"></i> Interests</h4>
                            <textarea name="interests" class="form-control" rows="4"><?php 
                                echo htmlspecialchars($profile_data['interests'] ?? ''); 
                            ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" name="<?php echo $profile_data ? 'update_profile' : 'create_profile'; ?>" 
                            class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> <?php echo $profile_data ? 'Update Profile' : 'Create Profile'; ?>
                    </button>
                    
                    <?php if ($profile_data): ?>
                        <a href="profiles.php?id=<?php echo $profile_id; ?>" class="btn btn-secondary btn-lg ml-2">
                            Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Preview profile image when selected
        document.getElementById('profilePictureUpload').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>