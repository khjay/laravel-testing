<?php

namespace Tests\Feature;

use Mail;
use Tests\TestCase;
use Tests\MailTracking;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use MailTracking;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        Mail::raw('Hello world', function($message) {
            $message->to('foo@bar.com');
            $message->from('bar@foo.com');
        });

        $this->seeEmailsSent(1)
             ->seeEmailTo('foo@bar.com')
             ->seeEmailFrom('bar@foo.com')
             ->seeEmailEquals('Hello world')
             ->seeEmailContains('Hello world');
    }
}
