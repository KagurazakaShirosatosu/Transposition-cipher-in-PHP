<?php

$password = [8,1,6,3,5,7,4,9,2];
$plain = '4D6165532D4F69204E';

$cipher = encrypt($plain, $password);
$plain = decrypt($cipher, $password);

var_dump($cipher);
var_dump($plain);

function encrypt($plain, $password) {
    $plain = str_replace(' ', '', $plain);
    loop: preg_match_all("#[0-9a-fA-FZZ]{2}#", $plain, $plainHex);
    $plainHex = $plainHex[0];
    $mod = count($plainHex) % count($password);
    if(count($plainHex) % count($password) != 0){
        $count = count($password) - (count($plainHex) % count($password));
        echo $count . PHP_EOL;
        for($i = 0; $i < $count; $i++) {
            $plain = $plain . 'ZZ';
        }
        goto loop;
    }
    $regex = '#[0-9a-fA-FZZ]{' . count($password)*2 . '}#';
    preg_match_all($regex, $plain, $plainHex1);
    $plainHex1 = $plainHex1[0];
    $count = count($plainHex1);
    for($i = 0; $i < $count; $i++) {
        @ $cipher = $cipher . parted_encrypt($plainHex1[$i], $password);
    }
    return $cipher;
}

function decrypt($cipher, $password) {
    $regex = '#[0-9a-fA-FZZ]{' . count($password)*2 . '}#';
    preg_match_all($regex, $cipher, $plainHex);
    $plainHex = $plainHex[0];
    for($i = 0; $i < count($plainHex); $i++) {
        @ $plain = $plain . parted_decrypt($plainHex[$i], $password);
    }
    $plain = preg_replace("#ZZ*$#", '', $plain);
    return $plain;
}

function parted_encrypt($plain, $password) {
    preg_match_all("#[0-9a-fA-FZZ]{2}#", $plain, $hex);
    $hex = $hex[0];
    $count = count($password);
    for($i = 0; $i < $count; $i ++) {
        $password[$i] = $password[$i] -1;
    }
    $count = count($hex);
    for($i = 0; $i <= $count; $i++) {
        @ $cipher = $cipher . $hex[$password[$i]];
    }
    return $cipher;
}

function parted_decrypt($cipher, $password) {
    preg_match_all("#[0-9a-fA-FZZ]{2}#", $cipher, $hex);
    $hex = $hex[0];
    if(count($hex) != count($password)) {
        return false;
    }
    $count = count($password);
    for($i = 0; $i < $count; $i ++) {
        $array[$password[$i] -1] = $i;
    }
    for($i = 0; $i < $count; $i++) {
        $plain = $plain . $hex[$array[$i]];
    }
    return $plain;
}