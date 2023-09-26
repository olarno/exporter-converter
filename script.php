<?php

use FunnyQuoteLib\FunnyQuoteLibrary;
require 'vendor/autoload.php';



$quoteLibrary = new FunnyQuoteLibrary();
$quote = $quoteLibrary->getRandomQuote();

echo "Citation du jour : " . $quote;

// *** Script to export data from a csv to a json format *** //

/* Goals: 
 * - Get information from the CSV 
 *      - First row should be the title 
 * - Merge data per column to data array in Json
 * - Display the Json in Html
 */

/** Const definition **/
const DIRECTORY = 'Documentation';

function export_to_json()
{
    $return_data = [];

    if (($handle = fopen("Documentation/test.csv", "r")) !== FALSE) {

        $tittles = [];

        $i = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //fetch fields row by row from csv 
            if ($i === 0) {
                $return_data = [
                    $data[0] => [],
                    $data[1] => [],
                    $data[2] => [],
                ];
                $tittles = $data;
            } else {
                array_push($return_data[$tittles[0]], $data[0]);
                array_push($return_data[$tittles[1]], $data[1]);
                array_push($return_data[$tittles[2]], $data[2]);
            }

            $i++;
        }


        fclose($handle);
    }

    return json_encode($return_data, JSON_FORCE_OBJECT);
}

function display_result($data)
{
    $array_decode = json_decode($data, true);
    $titles = array_keys($array_decode);

    echo '<table>';
    echo '<tr>';
    echo '<th>' . $titles[0] . '</th>';
    foreach ($array_decode[$titles[0]] as $data) {
        echo '<td>' . $data . '</td>';
    };
    echo '</tr>';
    echo '<tr>';
    echo '<th>' . $titles[1] . '</th>';
    foreach ($array_decode[$titles[1]] as $data) {
        echo '<td>' . $data . '</td>';
    };
    echo '</tr>';
    echo '<tr>';
    echo '<th>' . $titles[2] . '</th>';
    foreach ($array_decode[$titles[2]] as $data) {
        echo '<td>' . $data . '</td>';
    };
    echo '</tr>';
    echo '</table>';
}


function files_in_directory()
{
    return array_diff(scandir(DIRECTORY), array('..', '.'));
}

function display_files_in_directory($array_files)
{
    echo '<ul>';
    foreach ($array_files as $filename) {
        echo '<li>' . $filename . '</li>';
    }
    echo '</ul>';
}


$array_files = files_in_directory();
$data = export_to_json();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Export csv to json</title>
</head>

<body>
    <h1>Exporter to Json</h1>
    <h2>Display result in html </h2>

    <p>There is a list of accessible files: <?php display_files_in_directory($array_files) ?></p>
    <p>Add new files</p>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="text" name="nom" id="nom">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit">Submit</input>
    </form>


    <?php display_result($data); ?>

</body>

</html>