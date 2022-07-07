<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Url;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_validator()
    {
        $data = $this->getUserData();

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);

        $access_token = $response->json()['access_token'];
        $post_data = $this->getPostData();

        $title = $post_data['title'];
        unset($post_data['title']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $access_token)
            ->postJson(Url::create_post, $post_data);
        $response->assertStatus(400);
        $response->assertSeeText("The title field is required");

        $post_data['title'] = $title;
        unset($post_data['description']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $access_token)
            ->postJson(Url::create_post, $post_data);
        $response->assertStatus(400);
        $response->assertSeeText("The description field is required");
    }

    public function test_create()
    {
        $data = $this->getUserData();

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);

        $access_token = $response->json()['access_token'];
        $user_id = $response->json()['id'];
        $post_data = $this->getPostData();

        $response = $this->withHeader('Authorization', 'Bearer ' . $access_token)
            ->postJson(Url::create_post, $post_data);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'title',
            'description',
            'user_id'
        ]);
        $this->assertTrue($response->json()['user_id'] === $user_id);
    }

    public function test_show_all()
    {
        $data = $this->getUserData();

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);

        $access_token = $response->json()['access_token'];
        $post_data = $this->getPostData();

        for ($n = 0; $n < 10; $n++) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $access_token)
                ->postJson(Url::create_post, $post_data);
            $response->assertStatus(201);
        }

        $response = $this->get(Url::posts);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
        $this->assertTrue(count($response->json()['data']) == 10);
    }

    public function test_show_single()
    {
        $data = $this->getUserData();

        $response = $this->postJson(Url::register, $data);
        $response->assertStatus(200);

        $access_token = $response->json()['access_token'];
        $post_data = $this->getPostData();
        $ids = [];

        for ($n = 0; $n < 5; $n++) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $access_token)
                ->postJson(Url::create_post, $post_data);
            $response->assertStatus(201);
            $ids[] = $response->json()['id'];
        }

        $random = rand($ids[0], $ids[4]);
        $response = $this->get(Url::posts . "/" . $random);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'description',
            'publication_date',
            'user_id'
        ]);
    }
}
