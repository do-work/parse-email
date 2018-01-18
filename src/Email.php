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
    public function getDisplayValues()
    {
        //todo - add input for how many emails to check
        $connected = $this->connect();
        $rawEmail = [];
        $bodyResults = [];
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
        $allEmailDisplayValues = [];

        for ($i = 1; $i <= $this->getEmailQty(); $i++) {
            $rawEmail[] = imap_fetchbody($connected, $i, 0);
        }

        for ($i = 0; $i <= count($rawEmail) - 1; $i++) {
            preg_match_all("/(^[A-Z].+):([ ]+[\S ]*)/m", $rawEmail[$i], $bodyResults);
            $newArr[] = array_combine($bodyResults[1], $bodyResults[2]);
            $allEmailDisplayValues[] = array_intersect_key($newArr[$i], array_flip($displayValuesArr));

        }

        return $allEmailDisplayValues;
    }



}