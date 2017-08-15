<?php
/**
 * Research/Mailer
 *
 * @author Joshua Copeland <JoshuaRCopeland@gmail.com>
 */

namespace Research;


class Mailer
{

    /**
     * @var \Swift_SmtpTransport
     */
    private $transport;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Mailer constructor.
     * @param $smtpHost
     * @param $username
     * @param $password
     * @param int $smtpPort
     */
    public function __construct($smtpHost, $username, $password, $smtpPort = 25)
    {
        // Create the Transport
        $this->transport = (new \Swift_SmtpTransport($smtpHost, $smtpPort))
            ->setUsername($username)
            ->setPassword($password);

        // Create the Mailer using your created Transport
        $this->mailer = new \Swift_Mailer($this->transport);

    }

    /**
     * @param $to array|string Array can be key=email and value=name or just the email can be passed in
     * @param $from array|string Array can be key=email and value=name or just the email can be passed in
     * @param $subject string The subject of the email
     * @param $body string The body of the email
     * @param $contentType string The body content type for the email
     * @throws \InvalidArgumentException If subject or body is empty
     * @return int The number of successful recipients. 0 Can indicate failure.
     */
    public function sendEmail($to, $from, string $subject, string $body, string $contentType = 'text/html')
    {
        if (!is_iterable($to)) {
            $to = [$to];
        }
        if (!is_iterable($from)) {
            $from = [$from];
        }
        if (!$subject || !$body) {
            throw new \InvalidArgumentException('Subject or body was empty! Aborting send mail.');
        }

        // Create a message
        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType);

        // Send the message
        $result = $this->mailer->send($message);
        return $result;
    }
}
