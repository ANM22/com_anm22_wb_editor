<?php
/**
 * @deprecated since version 3.12
 * 
 * Author: ANM22
 * Last modified: 20 Sep 2020 - GMT +2 11:59
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* MAIL SENDER */
$from = $_POST['wb_ms_from'];
$to = $_POST['wb_ms_to'];
$obj = $_POST['wb_ms_obj'];
$msg = $_POST['wb_ms_msg'];
$redirect = $_POST['wb_ms_redirect'];

$result = @mail($to, $obj, $msg,
                "From: " . $from . "\r\n" .
                "X-Mailer: PHP/" . phpversion());

$result_msg = "";
if (!$result) {
    $result_msg = "&ms=failed";
}

header("Location: " . $redirect . $result_msg);
exit;