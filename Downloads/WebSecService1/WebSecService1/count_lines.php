<?php

// Check if data.txt exists
if (!file_exists('data.txt')) {
    die("Error: data.txt file not found!\n");
}

// Count the number of lines in data.txt
$lineCount = count(file('data.txt'));

// Write the count to line_count.txt
if (file_put_contents('line_count.txt', "Number of lines: $lineCount") === false) {
    die("Error: Could not write to line_count.txt\n");
}

echo "Successfully counted $lineCount lines and saved to line_count.txt\n"; 