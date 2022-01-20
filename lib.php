<?php

if ($data = json_decode(file_get_contents('php://input'), true)) {
    $_POST = $data;
}

function cors()
{

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}


function setAchat($id, $achat)
{
    $file = './data/achats.json';
    $content = charger($file);

    $content[$id] = $achat;

    if (sauver($file, $content)) {
        return $content;
    }
}

function setReception($id, $achat)
{
    $file = './data/receptions.json';
    $content = charger($file);

    $content[$id] = $achat;

    if (sauver($file, $content)) {
        return $content;
    }
}


function charger($file)
{
    $content = @file_get_contents($file);
    if ($content) {
        $content = json_decode($content, true);
    } else {
        $content = [];
    }
    return $content;
}

function sauver($file, $content) {
    $tmp = './cache/'.sha1(microtime());
    if(file_put_contents($tmp, json_encode($content, JSON_PRETTY_PRINT))) {
        return rename($tmp, $file);
    }
}