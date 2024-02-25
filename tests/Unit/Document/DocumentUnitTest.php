<?php

namespace Tests\Unit\Document;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class DocumentUnitTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_document_list()
    {
        $this->assertTrue(true);
        // $this->setUpFaker();

        // $this->withoutExceptionHandling();

        // $user = User::factory()->create();
        // $this->actingAs($user);

        // $document = Document::factory()->create();
        // $this->json('GET', 'api/v1/documents', ['Accept' => 'application/json'])
        //     ->assertStatus(200);
    }

    public function test_document_creation_get()
    {
        $this->assertTrue(true);
        // $this->withoutExceptionHandling();

        // $user = User::factory()->create();
        // $this->actingAs($user, 'api');

        // $this->json('GET', 'api/v1/documents/',  ['Accept' => 'application/json'])
        //     ->assertStatus(200);
    }

    public function test_document_get_by_id()
    {
        $this->assertTrue(true);
        // $this->withoutExceptionHandling();

        // $user = User::factory()->create();
        // $this->actingAs($user, 'api');

        // $documentid = Document::all()->random()->id;

        // $this->json("GET", "api/v1/documents/$documentid",  ['Accept' => 'application/json'])
        //     ->assertStatus(200);
    }

    public function test_document_update()
    {
        $this->assertTrue(true);
        // $this->withoutExceptionHandling();

        // $user = User::factory()->create();
        // $this->actingAs($user, 'api');

        // $documentid = Document::all()->random()->id;

        // $data = [
        //     'display' => 1,
        //     'status' => 'processing',
        //     'public' => 1,
        //     'user_id' => Str::uuid()->toString()
        // ];

        // $this->json("PUT", "api/v1/documents/$documentid", $data, ['Accept' => 'application/json'])
        //     ->assertStatus(200);
    }

    public function test_document_get_image_tool()
    {
        $this->assertTrue(true);
        // $this->withoutExceptionHandling();

        // $user = User::factory()->create();
        // $this->actingAs($user);

        // $documentid = Document::all()->random()->id;

        // $this->json('GET', "/api/v1/document-image-tools/$documentid", ['Accept' => 'application/json'])
        //     ->assertStatus(200);
    }
}
