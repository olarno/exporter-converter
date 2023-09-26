<?php
$target_dir = "Documentation/";
$errors = array();
$authorized_type = ['csv'];

if (isset($_FILES['fileToUpload'])) {
    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_type = $_FILES['fileToUpload']['type'];
    $explode_file = explode('.', $_FILES['fileToUpload']['name']);
    $file_ext = strtolower(end($explode_file));

    var_dump($file_ext);

    if (in_array($file_ext, $authorized_type) === false) {
        $errors[] = "extension not allowed, please choose a CSV file.";
    }

    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, $target_dir . $file_name);
        echo "Success";
    } else {
        print_r($errors);
    }

}
