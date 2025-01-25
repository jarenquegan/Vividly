<?php
// convert.php

// Specify the source PHP file and the destination HTML file
$sourceFile = 'support.php';
$outputFile = 'output.html';

// Use output buffering to capture the output of the PHP file
ob_start();
include($sourceFile);
$htmlContent = ob_get_contents();
ob_end_clean();

// Write the HTML content to the output file
file_put_contents($outputFile, $htmlContent);

echo "Conversion complete. The HTML file is saved as {$outputFile}.";
?>