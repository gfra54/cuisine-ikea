<?php include 'lib.php';
cors();

$ret=false;

if(isset($_POST['achat'])) {
    $ret = setAchat($_POST['id'], $_POST['achat']);
}

if(isset($_POST['reception'])) {
    $ret = setReception($_POST['id'], $_POST['reception']);
}



header('Content-Type: application/json; charset=utf-8');
echo json_encode($ret);