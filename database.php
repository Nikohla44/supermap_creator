<?php
try {
    $database = new PDO('mysql:host=localhost; dbname=avlm; charset=utf8', 'root', 'cam');
}
catch (Exception $e) {
    die('ERROR'.$e->getMessage());
}
