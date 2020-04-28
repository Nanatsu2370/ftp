<?php

namespace Tests\Unit;

use App\Category;
use App\Jobs\Processxlsx;
use App\Notifications\Done;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
class CatgoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test if the database is working correctly.
     * Add a root node.
     */
    public function testDatabase(){
        factory(Category::class)->state("root")->create();

        $this->assertDatabaseHas("category_map",[
            'parent_ID'=>0
        ]);
    }

    /**
     * Test if the worker/queue works correctly.
     * Fake it and dispatch.
    */
    public function testJobs(){
        Queue::fake();
        Processxlsx::dispatch();
        Queue::assertPushed(Processxlsx::class);
    }
    /**
     * Test if the notification system works correctly
     * Also counts as mail, because its the only option for now.
     */
    public function testNotification(){
        Notification::fake();
        Processxlsx::dispatchNow();
        Notification::assertSentTo(
            new AnonymousNotifiable,
            Done::class,
            function($_,$channel){
                return in_array('mail',$channel);
            }
        );
    }
}
