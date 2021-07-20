<?php
require_once __DIR__.'/vendor/autoload.php';
use ConfigManager\Store;

$store = new Store();
$store->init();
// $store->loadEnvironment();

print_r($store->listConfig());

// $namespaces=array();
// foreach(get_declared_classes() as $name) {
//     if(preg_match_all("@[^\\\]+(?=\\\)@iU", $name, $matches)) {
//         $matches = $matches[0];
//         $parent =&$namespaces;
//         while(count($matches)) {
//             $match = array_shift($matches);
//             if(!isset($parent[$match]) && count($matches))
//                 $parent[$match] = array();
//             $parent =&$parent[$match];

//         }
//     }
// }

// print_r($namespaces);
