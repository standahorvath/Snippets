<?php

/**
 * @author : Stanislav Horváth
 * @email : standa@tonakodi.cz
 * @version : 1.0
 * 
 * @description : This script is used to read data from Google Sheets API.
 */


/* Functions */


/** 
 * Description : This function is used to convert array to html table.
 * @param array $array : Array to be converted to HTML table.
 * @return string : HTML table.
 */

function array2html($array)
{
    $html = "<table>";
    foreach ($array as $row) {
        $html .= "<tr>";
        foreach ($row as $cell) {
            $html .= "<td>" . $cell . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

/**
 * @description : This function is used to print html code.
 * @param string $html : HTML code.
 */
function html($html)
{
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    die();
}

/**
 * @description : This function is used to print json code.
 * @param array $array : Array to be converted to JSON.
 */
function json($array)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($array);
    die();
}

/* Script */

$api_key = "<API_KEY_HERE>";    // Google Sheets API key
$sheet_id = "<SHEET_ID_HERE>";  // Google Sheets ID

$sheet_list = $_GET["sheet"];
$output = $_GET["output"];

if (empty($sheet_list)) {
    json(array("error" => "sheet not specified", "result" => false));
}

$data = @file_get_contents("https://sheets.googleapis.com/v4/spreadsheets/" . $sheet_id . "/values/" . $sheet_list . "?key=" . $api_key);

if (empty($data)) {
    json(array("error" => "no data", "result" => false));
}

$parsed_data = json_decode($data);
$rows = ((array)$parsed_data)["values"];

if ($output == "json") {
    json(array("error" => null, "result" => true, "data" => $rows));
} else if ($output == "html") {
    html(array2html($rows));
} else {
    json(array("error" => "output not specified", "result" => false));
}
