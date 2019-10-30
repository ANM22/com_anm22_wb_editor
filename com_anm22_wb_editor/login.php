<?php
/*
 * Author: ANM22
 * Last modified: 06 Oct 2014 - GMT +2 02:22
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* -> inizializzazione della sessione */
session_start();
/* <- */

/* MAIL SENDER */
$_SESSION['com_anm22_wb_login'] = $_GET['id'];
$callback_url = $_GET['callback_url'];
header( "Location: ".$callback_url );
exit;
?>