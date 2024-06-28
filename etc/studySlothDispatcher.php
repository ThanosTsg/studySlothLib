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
header("Content-Type: application/json;charset=UTF-8");

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

function checkIfEmpty($term)
{
    if(!empty(trim($term))){
        return false;
    }
    parseResponse("bad", "Write something!");
    return true;
}

function parseResponse($class = '', $msg = ''){
    echo json_encode(['slothResp' => [
            'class' => $class,
            'msg'   => $msg
        ]]);
    exit();
}

if (isset($_POST["searchkey"]) && !checkIfEmpty($_POST["searchkey"])) {
    if (searchEntries($_POST["searchkey"])) {
       return parseResponse("bad", "Song Name Found.");
    } else {
        return parseResponse("positive", "Song Name NOT Found.");
    }
}

if (isset($_POST["addentry"]) && !checkIfEmpty($_POST["addentry"])) {
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
        return parseResponse("positive", "Song successfully added!");
    } else {
        return parseResponse("bad", "Song is already added.");
    }
}
