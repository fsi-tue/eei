<?php

// Include the config file to get the filepath $fp
require 'config.php';

// Check if files can be written to $fp
$testFile = $fp . '/healthcheck_test.txt';
$testString = 'Healthcheck test string';

// Remove test file if it already exists
if (file_exists($testFile)) {
    unlink($testFile);
}

// Try writing to a test file
if (file_put_contents($testFile, $testString) === false) {
    echo "Error: Unable to write to the specified file path in config.php\n";
    exit(1); // Indicate an error
}

// Clean up the test file
unlink($testFile);

echo "Healthcheck passed!\n";
exit(0);
