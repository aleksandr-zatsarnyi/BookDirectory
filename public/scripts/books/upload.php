<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $file = $_FILES["image"];
    $uploadDirectory = "uploads/";

    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $uniqueFilename = uniqid() . "_" . $file["name"];
    $targetPath = $uploadDirectory . $uniqueFilename;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        echo "picture is load: " . $uniqueFilename;
    } else {
        echo "failed to load picture.";
    }
}