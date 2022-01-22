<?php include 'lib.php';
cors();

$w = $_GET['w'];
$ret = false;

if ($w == 'groupes') {
    $ret = charger('ikea.json');
} else {
    if (isset($_POST['meta'])) {
        $ret = setMeta($_POST['id'], $_POST['meta']);
    } else {
        if ($w == 'metas') {
            $ret = charger('data/metas.json');
        }
    }
    if (isset($_POST['achat'])) {
        $ret = setAchat($_POST['id'], $_POST['achat']);
    } else {
        if ($w == 'achats') {
            $ret = charger('data/achats.json');
        }
    }

    if (isset($_POST['reception'])) {
        $ret = setReception($_POST['id'], $_POST['reception']);
    } else {
        if ($w == 'receptions') {
            $ret = charger('data/receptions.json');
        }
    }
}



header('Content-Type: application/json; charset=utf-8');
echo json_encode($ret);
