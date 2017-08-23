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

    /** @test */
    public function a_user_can_like_a_post()
    {
        // given I have a post
        $post = factory(Post::class)->create();

        // and a user
        $user = factory(User::class)->create();
        
        // and that user is logged in
        $this->actingAs($user);

        // when they like a post
        $post->like();

        // then we should see evidence in the database, and the post should be liked.
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post),
        ]);

        $this->assertTrue($post->isLiked());
    }

    /** @test */
    public function a_user_can_unliked_a_post()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();
        
        $this->actingAs($user);

        $post->like();
        $post->unlike();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_post_like_status()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();
        
        $this->actingAs($user);

        $post->toggle();
        $this->assertTrue($post->isLiked());

        $post->toggle();
        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();
        
        $this->actingAs($user);

        $post->toggle();
        
        $this->assertEquals(1, $post->likesCount);
    }
}
