<?php

require './vendor/autoload.php';
require './src/Contact.php';

use PHPUnit\Framework\TestCase;


final class ContactTest extends TestCase
{
    /** @test */
    public function sendRequestContact()
    {
        $this->assertContains('@', 'user@example.com');
    }

    /** @test */
    public function setRequestContact()
    {
        $this->assertInstanceOf(Contact::class, Contact::setContact('user@example.com', 'sujet', 'message'));
    }
}
