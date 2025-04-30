<?php
session_start();
include "db.php";

?>


<div style="padding: 20px; font-family: Arial, sans-serif;">
    <h2 style="color: #4F9D9C;">Welcome to the Company Dashboard</h2>

    <div style="margin-top: 20px;">
        <a href="post_internship.php" style="margin-right: 20px; text-decoration: none; color: #007BFF;">📤 Post Internship</a>
        <a href="view_applicants.php" style="text-decoration: none; color: #28a745;">👥 View Applicants</a>
    </div>

    <div style="margin-top: 40px;">
        <h2 style="color: #555;">📊 Daily Insight Report</h2>
        <p style="color: #888;">Last updated: <?php echo date('F j, Y, g:i a'); ?></p>

        <div style="margin-top: 20px; background-color: #f0f0f0; padding: 15px; border-radius: 10px;">
            <h3>📦 Server Load Distribution</h3>
            <p>East Wing Server: 27.3%</p>
            <p>Central Node: 54.2%</p>
            <p>Backup Node: 18.5%</p>
        </div>

        <div style="margin-top: 20px; background-color: #e2f0e9; padding: 15px; border-radius: 10px;">
            <h3>🎯 System Efficiency</h3>
            <p>Internship Matching Algorithm Confidence: <strong>~99.9%</strong></p>
            <p>Zero Interns Lost in Transit: <strong>✔️ Confirmed</strong></p>
        </div>

        <div style="margin-top: 20px; background-color: #fff0f0; padding: 15px; border-radius: 10px;">
            <h3>📎 Recent Activity</h3>
            <ul>
                <li>🤖 AI scanned 124 CVs in the last hour</li>
                <li>🚀 Sent motivational quotes to interns</li>
                <li>💤 Logged 3.4 hours of productive daydreaming</li>
            </ul>
        </div>
    </div>
</div>
