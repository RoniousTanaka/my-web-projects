

<!-- Dashboard -->
<div class="dashboard-wrapper">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h2>Welcome to your Dashboard</h2>
        <p>Hi, <?php echo "Student #$student_id"; ?>! Here's your overview.</p>
    </div>

    <!-- Info Cards -->
    <div class="info-cards">
        <div class="card">
            <h3>Application Status</h3>
            <p>Your current internship application status is: <span class="status"><?php echo $app_status['status']; ?></span></p>
        </div>
        <div class="card">
            <h3>Upcoming Opportunities</h3>
            <p>You have <?php echo $opportunities_result->num_rows; ?> new internship opportunities to explore!</p>
        </div>
        <div class="card">
            <h3>Profile Completion</h3>
            <p>Your profile is <?php echo $profile_data['profile_complete']; ?>% complete. Update it for better opportunities!</p>
        </div>
    </div>

    <!-- Notifications -->
    <div class="notifications">
        <?php while($notification = $notifications_result->fetch_assoc()): ?>
            <div class="notification-item">
                <h4><?php echo $notification['message']; ?></h4>
                <span><?php echo time_ago($notification['created_at']); ?></span>
            </div>
        <?php endwhile; ?>
    </div>
</div>
