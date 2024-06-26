<?php

/**
 * studySlothDispatcher.php
 *
 * Receives requests , reads & writes to the primitive JSON DB
 *
 * @author     Thanos Tsagris
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$result = "";
$output_class = "";
$slothData = json_decode(file_get_contents("studySlothData.json"), true);

function searchEntries($key)
{
    global $slothData;
    foreach ($slothData["studySlothSongsData"] ?? [] as $row) {
        if ($row["name"] == trim($key)) {
            return true;
        }
    }
    return false;
}

if (isset($_POST["searchkey"])) {
    if (searchEntries($_POST["searchkey"])) {
        $output_class = "bad";
        $result = "Song Name Found.";
    } else {
        $output_class = "positive";
        $result = "Song Name NOT Found.";
    }
}

if (isset($_POST["addentry"])) {
    if (!searchEntries($_POST["addentry"])) {
        $slothData["studySlothSongsData"][] = [
            "name" => trim($_POST["addentry"]),
            "date" => (new DateTime(
                "now",
                new DateTimeZone("Europe/Athens")
            ))->format("l, d M Y H:i"),
            "meta" => "",
        ];
        file_put_contents(
            "studySlothData.json",
            json_encode($slothData),
            LOCK_EX
        );
        $output_class = "positive";
        $result = "Song successfully added!";
    } else {
        $output_class = "bad";
        $result = "Song is already added.";
    }
}
echo '<span class="text msg_' .
    $output_class .
    '">' .
    $result .
    '</span><span class="emoji_msg_' .
    $output_class .
    '"></span>';
