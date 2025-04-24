<?php
require_once __DIR__.'/auth_check.php';

$section = $_GET['section'] ?? 'guides';
$allowed = ['guides', 'clients'];

if(in_array($section, $allowed)) {
    header("Location: $section/index.php");
} else {
    die("Section non valide");
}
