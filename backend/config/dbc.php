<?php
if (!defined('DB_HOST'))
    define('DB_HOST', "wordpressdb-j.hosting.stackcp.net");
if (!defined('DB_USER'))
    define('DB_USER', 'SCWORDPRESS-3135319693');
if (!defined('DB_PASS'))
    define('DB_PASS', 'CybertronDB@2025');
if (!defined('DB_NAME'))
    define('DB_NAME', 'SCWORDPRESS-3135319693');

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>