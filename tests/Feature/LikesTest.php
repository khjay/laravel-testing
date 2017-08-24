<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikesTest extends TestCase
{
    use DatabaseTransactions;

    protected $post;

    public function setUp()
    {
        parent::setUp();
        $this->post = factory(Post::class)->create();
    }

    /** @test */
    public function a_user_can_like_a_post()
    {
        // given I have a post

        // and a user

        // and that user is logged in

        $this->signIn();

        // when they like a post
        
        $this->post->like();

        // then we should see evidence in the database, and the post should be liked.
        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post),
        ]);

        $this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_unliked_a_post()
    {
        $this->signIn();

        $this->post->like();
        $this->post->unlike();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_post_like_status()
    {
        $this->signIn();

        $this->post->toggle();
        $this->assertTrue($this->post->isLiked());

        $this->post->toggle();
        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has()
    {
        $this->signIn();

        $this->post->toggle();
        
        $this->assertEquals(1, $this->post->likesCount);
    }
}
