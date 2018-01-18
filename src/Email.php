<?php

namespace Email;

class Email
{

    private $account;
    private $username;
    private $password;

    /**
     * Email constructor.
     * @param $account
     * @param $username
     * @param $password
     */
    public function __construct($account, $username, $password)
    {
        // todo - add conditional for email type, google, etc, throw exception for those not listed
        // todo - allow for checking different inboxes

        $this->account = $account;
        $this->username = $username;
        $this->password = $password;
    }


    /**
     * @return resource
     * @throws \Exception
     */
    private function connect()
    {
        $connection = imap_open($this->account, $this->username, $this->password);
        if ($connection == false) {
            $imapError = imap_last_error();
            throw new \Exception("Error Connecting: $imapError");
        }

        return $connection;
    }

    /**
     * @return mixed
     */
    public function getEmailQty()
    {
        $info = imap_check($this->connect());

        return $info->Nmsgs;
    }

    /**
     * @return array
     */
    public function getRawEmail()
    {
        //todo - add input for how many emails to check
        $connected = $this->connect();
        $rawEmail = [];
        $results = [];
        $finalResults = [];

        for ($i = 1; $i <= $this->getEmailQty(); $i++) {
            $rawEmail[] = imap_fetchbody($connected, $i, 0);
        }

        foreach ($rawEmail as $email) {
            preg_match_all("/(^[A-Z].+):([ ]+[\S ]*)/m", $email, $results);
        };
        $results = array_combine($results[1], $results[2]);

        $displayValuesArr = [
            "Delivered-To",
            "Received",
            "Return-Path",
            "Sender",
            "From",
            "Date",
            "Subject",
            "To",
            "Content-Type"
        ];

        foreach ($results as $k => $v) {
            foreach ($displayValuesArr as $displayTitle => $displayValue) {
                if ($k == $displayValue) {
                    $finalResults[$k] = $v;
                }
            }
        }
        return $finalResults;
    }

}