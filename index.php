<?php
require "vendor/autoload.php";

use Email\Email;

$gmail = new Email("{imap.gmail.com:993/ssl}INBOX", "dowork87775", "thisisatestemailforwork");
$gmail->connect();
print_r($gmail->getRawEmail());