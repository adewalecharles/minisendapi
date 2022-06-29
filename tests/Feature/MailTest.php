<?php

namespace Tests\Feature;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register()
    {
       $response = $this->post('api/v1/register', [
           'username' => 'jhondoe',
           'email' => 'jhondoe@test.com',
          'password' => '123456789',
          'password_confirmation' => '123456789',
         ]);
        $response->assertStatus(200);
    }

    public function test_user_can_login()
    {
        $response = $this->post('api/v1/login', [
            'email' => 'jhondoe@test.com',
            'password' => '123456789',
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_send_mail()
    {
        $user = User::first();
        // post with token from login
        $response = $this->actingAs($user,'web')->post('api/v1/email', [
            'from' => 'test@test.com',
            'to' => 'test1@test.com',
            'subject' => 'test',
            'text_content' => 'test',
            'html_content' => '<h1>test</h1>',
            'webhook_url' => 'http://test.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_get_mails()
    {
        $user = User::first();
        $response = $this->actingAs($user, 'web')->get('api/v1/get-emails');

        $response->assertStatus(200);
    }

    public function test_user_can_get_mail()
    {
        $user = User::first();
        $mail = $user->mails()->first();
        $response = $this->actingAs($user, 'web')->get('api/v1/get-email/'.$mail->uuid);
        $response->assertStatus(200);
    }

}
