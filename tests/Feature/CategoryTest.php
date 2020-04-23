<?php

namespace Tests\Feature;

use App\Mail\ProcessingDone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        //Testing response
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testDatabase(){
        $this->assertDatabaseHas("category_map",[
            'parent_ID'=>0
        ]);
    }

    public function testMail(){
        Mail::fake();
        Mail::assertQueued(ProcessingDone::class,function($mail){
            return $mail->hasTo(env("MAIL_TO"));
        });
    }
}
