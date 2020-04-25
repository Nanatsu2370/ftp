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

    public function testDatabase(){
        factory(Category::class)->state("root")->create();

        $this->assertDatabaseHas("category_map",[
            'parent_ID'=>0
        ]);
    }

    public function testJobs(){
        Queue::fake();
        Processxlsx::dispatch();
        Queue::assertPushed(Processxlsx::class);
    }
    /**
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
