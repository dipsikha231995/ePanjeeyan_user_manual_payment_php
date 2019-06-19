<?php

try {
	$pdo = new PDO('mysql:host=localhost;dbname=metadatabase;charset=utf8mb4', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);	
} 
catch (Exception $ex) {
	exit($ex->getMessage());
}