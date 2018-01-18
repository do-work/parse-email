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

        for ($i = 1; $i <= $this->getEmailQty(); $i++) {
            $rawEmail[] = imap_fetchbody($connected, $i, 0);
        }

        foreach ($rawEmail as $email) {

            preg_match_all("/(^[A-Z].+):([ ]+[\S ]*)/m", $email, $results);
        };
        $results = array_combine($results[1], $results[2]);

        unset($results['X-Received']);
        unset($results['ARC-Seal']);
        unset($results['ARC-Message-Signature']);
        unset($results['ARC-Authentication-Results']);
        unset($results['Authentication-Results']);
        unset($results['Authentication-Results']);
        unset($results['DKIM-Signature']);
        unset($results['X-Gm-Message-State']);
        unset($results['X-Google-Smtp-Source']);
        unset($results['X-Google-Sender-Auth']);
        unset($results['Message-ID']);

        return $results;

    }

}