<?php
require_once 'conf.php';
$directories = ['Global', 'Forms', 'Layouts'];

spl_autoload_register(function ($className) use ($directories) {
    foreach ($directories as $directory) {
        $filePath = __DIR__ . '/' . $directory . '/' . $className . '.php';
        echo "Looking for: $filePath<br>"; // DEBUG LINE
        if (file_exists($filePath)) {
            echo "Found: $filePath<br>"; // DEBUG LINE
            require_once $filePath;
            return;
        }
    }
    echo "Class '$className' not found in any directory!<br>"; // DEBUG LINE
});

//Create instances
$hello = new classes();
$form = new form();
$layout = new layout();
?>