<?php

/**
 * Endpoint to apply logged user ID 
 *
 * @author Andrea Menghi <andrea.menghi@anm22.it>
 */

/* PHP session init */
session_start();

/* Add user ID to the PHP session  */
$userId = $_GET['id'] ?? null;
$_SESSION['com_anm22_wb_login'] = $userId;

/* Check security key */
include __DIR__ . "/../../../config/license.php";
$token = $_GET['token'] ?? null;
$validToken = hash('sha256', "wbl" . md5($_SERVER['HTTP_HOST']) . $anm22_wb_license . md5($anm22_wb_licensePass) . $userId);

if (!$token || $token != $validToken) {
    http_response_code(401);
    echo json_encode(["error" => "Token not authorized"]);
    exit();
}

/* Redirect */
$callback_url = $_GET['callback_url'] ?? null;
if (!$callback_url) {
    $callback_url = "/";
}

header("Location: " . $callback_url);
exit;
