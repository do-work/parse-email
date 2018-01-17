<?php


class Email
{
    public $account;
    public $username;
    public $password;
    public $link;


    public function setParams()
    {
        // todo - add conditional for email type, google, etc, throw exception for those not listed
        // todo - allow for checking different inboxes
        return [
            $this->account = "{imap.gmail.com:993/ssl}INBOX",
            $this->username = "dowork87775",
            $this->password = "thisisatestemailforwork"
        ];
    }

    public function connect($account, $username, $password)
    {
        // todo - handle error as exception and log to file include imap_last_error
        $connection = $this->link = imap_open($account, $username, $password);
        if ($connection == false) {
            echo "you are not connected";
        }
    }

    public function getEmailQty()
    {
        $info = imap_check($this->link);

        return $info->Nmsgs;
    }

    public function getRawEmail()
    {
        //todo - add input for how many emails to check
        $connected = $this->link;
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

$gmail = new Email();
$credArr = $gmail->setParams();
$gmail->connect($credArr[0], $credArr[1], $credArr[2]);
print_r($gmail->getRawEmail());