<?php
// Check if ZipArchive is available
if (!class_exists('ZipArchive')) {
    die("❌ ZipArchive class not found. Please enable it in php.ini (extension=zip).");
}

$zip = new ZipArchive();
$filename = "laravel_recruitment_platform.zip";

// Try creating the zip file
if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
    exit("❌ Cannot open <$filename>\n");
}

// Simulated Laravel file structure and content
$files = [
    "app/Http/Controllers/InternController.php" => "<?php\n// InternController code here",
    "app/Http/Controllers/JobController.php" => "<?php\n// JobController code here",
    "app/Models/Intern.php" => "<?php\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass Intern extends Model {\n    protected \$fillable = ['name', 'email', 'phone', 'education', 'skills', 'experience', 'availability'];\n}",
    "app/Models/Job.php" => "<?php\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass Job extends Model {\n    protected \$fillable = ['title', 'description', 'required_skills', 'availability_required'];\n}",
    "database/migrations/2025_04_27_000000_create_interns_table.php" => "<?php\n// Migration for interns",
    "database/migrations/2025_04_27_000001_create_jobs_table.php" => "<?php\n// Migration for jobs",
    "resources/views/interns/index.blade.php" => "<!-- Intern Index View -->",
    "resources/views/interns/create.blade.php" => "<!-- Intern Create View -->",
    "resources/views/interns/edit.blade.php" => "<!-- Intern Edit View -->",
    "resources/views/interns/match.blade.php" => "<!-- Intern Match View -->",
    "resources/views/jobs/index.blade.php" => "<!-- Job Index View -->",
    "resources/views/jobs/create.blade.php" => "<!-- Job Create View -->",
    "resources/views/jobs/edit.blade.php" => "<!-- Job Edit View -->",
    "routes/web.php" => "<?php\n// Web routes"
];

// Add all files to the ZIP archive
foreach ($files as $path => $content) {
    $zip->addFromString($path, $content);
}

$zip->close();

echo "✅ ZIP file created successfully: <b>$filename</b>";
?>
