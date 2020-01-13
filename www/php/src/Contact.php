<?php

final class Contact
{
    private $email;
    private $sujet;
    private $message;

    private function __construct(string $email, string $sujet, string $message)
    {
        $this->email = $email;
        $this->sujet = $sujet;
        $this->message = $message;
    }

    public static function setContact(string $email, string $sujet, string $message)
    {
        return new self($email, $sujet, $message);
    }
}
