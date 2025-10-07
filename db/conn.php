<?php
session_start();

date_default_timezone_set('Asia/Jakarta');
$conn = mysqli_connect('localhost', 'root', '', 'sistem_konsinyasi');
if (!$conn) {
    die('database bermasalah');
}
?>