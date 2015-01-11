<?php
namespace Bolt\Components;

use Bolt\Exception\Error,
    Bolt\Exception\Exception;

/**
 * Class Email
 * @package Bolt\Components
 */
class Email {

    /**
     * @var null|\PHPMailer
     */
    private $PHPMailer = null;
    /**
     * @var bool
     */
    private $HTMLEmail = true;
    /**
     *
     */
    public function __construct() {
        $this->PHPMailer = new \PHPMailer();
        $this->PHPMailer->isHTML($this->HTMLEmail);
    }

    /**
     * @param string $email_address
     * @param string $name
     */
    public function setFromEmail($email_address, $name = '') {
        $this->PHPMailer->setFrom($email_address, $name);
    }

    /**
     * @param string $name
     */
    public function setFromName($name) {
        $this->PHPMailer->FromName = $name;
    }

    /**
     * @param string|array $email_addresses
     * @param string       $name
     */
    public function setToEmail($email_addresses, $name = '') {
        if (!is_array($email_addresses)) {
            $email_addresses = [
                $email_addresses => $name
            ];
        }

        foreach ($email_addresses as $email_address => $name) {
            $this->PHPMailer->addAddress($email_address, $name);
        }
    }

    /**
     * @param string|array $email_addresses
     * @param string       $name
     */
    public function setCcEmail($email_addresses, $name = '') {
        if (!is_array($email_addresses)) {
            $email_addresses = [
                $email_addresses => $name
            ];
        }

        foreach ($email_addresses as $email_address => $name) {
            $this->PHPMailer->addCC($email_address, $name);
        }
    }

    /**
     * @param string|array $email_addresses
     * @param string       $name
     */
    public function setBccEmail($email_addresses, $name = '') {
        if (!is_array($email_addresses)) {
            $email_addresses = [
                $email_addresses => $name
            ];
        }

        foreach ($email_addresses as $email_address => $name) {
            $this->PHPMailer->addBCC($email_address, $name);
        }
    }

    /**
     * @param string $email_address
     * @param string $name
     */
    public function setReplyToEmail($email_address, $name = '') {
        $this->PHPMailer->addReplyTo($email_address, $name);
    }

    /**
     * @param $html
     */
    public function setEmailBody($html) {
        $this->PHPMailer->Body = $html;
    }

    /**
     * @param $subject_line
     */
    public function setEmailSubject($subject_line) {
        $this->PHPMailer->Subject = $subject_line;
    }

    /**
     * @param $plain_text_body
     */
    public function setEmailPainTextBody($plain_text_body) {
        $this->PHPMailer->AltBody = $plain_text_body;
    }

    /**
     * @return bool
     */
    protected function isReadyToSend() {
        try {
            if ($this->PHPMailer->Body === '') {
                throw new Error('No email message body supplied, please call \Email->setEmailBody() before calling \Email->send().');
            } else if ($this->PHPMailer->Subject === '') {
                throw new Error('No email subject line supplied, please call \Email->setEmailSubject() before calling \Email->send().');
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Bolt\Exception\Warning
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function send() {
        if ($this->isReadyToSend()) {
            try {
                if (!$this->PHPMailer->send()) {
                    throw new Error($this->PHPMailer->ErrorInfo);
                }

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }
}