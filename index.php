<?php
require "vendor/autoload.php";

use Email\Email;

/**
 * gmail settings for imap, though this needs to be turned on within gmail:
 * imap.gmail.com:993
 * example: {imap.gmail.com:993/ssl}INBOX
 *
 * email - just username. No need for @gmail.com
 **/

try {
    $gmail = new Email("{imap.gmail.com:993/ssl}INBOX", "username", "password");
    print_r($gmail->getDisplayValues());
} catch (Exception $e) {
    error_log("Error Connecting:" . $e->getMessage());
}