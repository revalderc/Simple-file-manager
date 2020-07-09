<?php

namespace Tests\Feature;

use App\UploadedFile as UploadedFileModel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UploadFilesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        Event::fake();
    }

    /** @test */
    public function only_logged_in_users_can_see_the_files_list()
    {
        $response = $this->get('/files')->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_the_files_list()
    {
        $this->actingAsUser();

        $this->get('/files')->assertOk();
    }

    /** @test */
    public function a_file_can_be_added_through_the_form()
    {
        $this->actingAsUser();

        $response = $this->post('/files', $this->data());

        $this->assertCount(1, UploadedFileModel::all());
    }

    /** @test */
    public function a_title_is_required()
    {
        $this->actingAsUser();

        $response = $this->post('/files', array_merge($this->data(), ['title' => '']));

        $response->assertSessionHasErrors('title');
        $this->assertCount(0, UploadedFileModel::all());
    }

    /** @test */
    public function a_title_is_at_least_3_characters()
    {
        $this->actingAsUser();

        $response = $this->post('/files', array_merge($this->data(), ['title' => 'a']));

        $response->assertSessionHasErrors('title');
        $this->assertCount(0, UploadedFileModel::all());
    }

    /** @test */
    public function a_description_is_required()
    {
        $this->actingAsUser();

        $response = $this->post('/files', array_merge($this->data(), ['description' => '']));

        $response->assertSessionHasErrors('description');
        $this->assertCount(0, UploadedFileModel::all());
    }

    /** @test */
    public function a_tag_is_required()
    {
        $this->actingAsUser();

        $response = $this->post('/files', array_merge($this->data(), ['tags' => '']));

        $response->assertSessionHasErrors('tags');
        $this->assertCount(0, UploadedFileModel::all());
    }

    /** @test */
    public function a_file_is_required()
    {
        $this->actingAsUser();

        $response = $this->post('/files', array_merge($this->data(), ['file' => '']));

        $response->assertSessionHasErrors('file');
        $this->assertCount(0, UploadedFileModel::all());
    }

    /** @test */
    public function a_file_is_in_correct_format()
    {
        $this->actingAsUser();

        $response = $this->post('/files', array_merge($this->data(), [
            'file' => UploadedFile::fake()->image('cat.exe')
        ]));

        $response->assertSessionHasErrors('file');
        $this->assertCount(0, UploadedFileModel::all());
    }

    private function actingAsUser()
    {
        return $this->actingAs(factory(User::class)->create());
    }

    private function data()
    {
        return [
            'title' => 'Test title',
            'description' => 'Test description',
            'tags' => 'test, tag',
            'file' => UploadedFile::fake()->image('cat.jpg')
        ];
    }
}
