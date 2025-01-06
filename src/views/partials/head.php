<!-- 
ETML
Authors: Sebastien Tille
Date: January 6th, 2025
Description: This partial defines the <head> part of HTML / PHP documents. It
    imports the default stylesheet and the main javascript file to handle the 
    navbar and theme switching. The title defaults to Passion Lecture.
-->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "Passion Lecture"; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/main.js"></script>
</head>
<body>