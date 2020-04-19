<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAUserCanCreateAnArticle()
    {
        //Что
        $this->actingAs(factory(\App\User::class)->create(), 'api');
        //Где и когда
        $this->post('/posts', $attr = [
            'header'      => $this->faker->sentence(10),
            'description' => $this->faker->text(100),
            'text'        => $this->faker->text(500),
            'publish'     => 'on',
        ]);
        //В итоге
        $attr['publish'] = 1;
        $this->assertDatabaseHas('articles', $attr);
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
    }
}
