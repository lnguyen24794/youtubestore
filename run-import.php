<?php
// Load WordPress
require_once('/var/www/html/wp-load.php');

// Check if class exists
if (!class_exists('YoutubeStore_CSV_Import')) {
    require_once(get_template_directory() . '/inc/import/class-csv-import.php');
}


echo "DEBUG: Template Directory: " . get_template_directory() . "\n";
echo "Starting import...\n";

$importer = new YoutubeStore_CSV_Import();
$result = $importer->import_from_csv_files();

echo "Import Result:\n";
print_r($result);
