<?php


$file = 'liste achats cuisine ikea.txt';
//$file = 'test.txt';

$data = file_get_contents($file);
$data = str_replace("\t", ' ', $data);
$data = str_replace(",", '.', $data);
while (strstr($data, '  ')) {
    $data = str_replace('  ', ' ', $data);
}
$lines = explode(PHP_EOL, $data);
$lines = array_filter($lines, function ($item) {
    if (trim($item)) {
        return true;
    }
});
$groupe = false;
$ps = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($pid = getPid($line)) {
        $p = ['pid' => $pid];
        $p['nom'] = getNom($line);
        $p['product'] = getProduct($p);
        $p['groupe'] = $groupe;
        $p['qte'] = getQte($line);
        if(!$p['product']) {
            print_r($p);exit;
        }
        $ps[] = $p;
    } else {
        // if (is_numeric($line)) {
            $groupe = $line;
        // }
    }
}
$groups=[];
foreach($ps as $p) {
    if(!isset($groups[$p['groupe']])) {
        if(is_numeric($p['groupe'])) {
            $nom= 'Groupe '.$p['groupe'];
        } else {
            $nom = $p['groupe'];
        }
        $groups[$p['groupe']]=['nom'=>$nom,'ps'=>[],'total'=>0];
    }
    $groups[$p['groupe']]['ps'][]=$p;
    $groups[$p['groupe']]['total']+=$p['qte'];
}
file_put_contents('ikea.json', json_encode(array_values($groups), JSON_PRETTY_PRINT));
if(php_sapi_name() == 'cli') {
    echo count($ps) . ' lignes' . PHP_EOL;
}
function getPid($str)
{
    if ($str = trim($str)) {
        $tab = array_filter(explode(' ', $str));
        foreach ($tab as $item) {
            if (isPid($item)) {
                return $item;
            }
        }
    }
}
function getQte($str)
{
    if ($str = trim($str)) {
        $tab = array_filter(explode(' ', $str));
        if (is_numeric($tab[0])) {
            return $tab[0];
        }
    }
}
function isPid($item)
{
    if ($item) {
        $tmp = array_filter(explode('.', $item));
        if (count($tmp) == 3) {
            if (is_numeric(implode('', $tmp))) {
                return $item;
            }
        }
    }
}
function getNom($str)
{
    if ($str = trim($str)) {
        $tab = array_filter(explode(' ', $str));
        if (is_numeric($tab[0])) {
            unset($tab[0]);
        }
        $ret = [];
        foreach ($tab as $item) {
            if (isPid($item)) {
                return implode(' ', $ret);
            } else {
                $ret[] = $item;
            }
        }
    }
}


function getProduct($query)
{

    if($query['pid'] == '203.592.29') {
        return json_decode('{
              "name": "HAVSEN",
              "typeName": "Évier avec face avant visible",
              "itemMeasureReferenceText": "62x48 cm",
              "mainImageUrl": "https://www.ikea.com/fr/fr/images/products/havsen-evier-avec-face-avant-visible-blanc__0568387_pe665442_s5.jpg?f=s",
              "pipUrl": "https://www.ikea.com/fr/fr/p/havsen-evier-avec-face-avant-visible-blanc-20359229/",
              "priceNumeral": 169.0
          }',true);
    }
    foreach ($query as $term) {
        $url = 'https://sik.search.blue.cdtapps.com/fr/fr/search-box?q=' . explode(' ',str_replace('.', '', $term))[0];
        $cache = './cache/' . sha1($url);
        if (!file_exists($cache)) {
            $content = file_get_contents($url);
            file_put_contents($cache, $content);
        } else {
            $content = file_get_contents($cache);
        }
        if ($content) {
            $json = json_decode($content, true);
            // echo $url.PHP_EOL;
            if (isset($json['searchBox']['universal'][0]['product'])) {
                return $json['searchBox']['universal'][0]['product'];
            }
        }
    }
}
