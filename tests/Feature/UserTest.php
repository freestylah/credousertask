<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
  // If the registration is successfull it'll give code 201, if email already exists 422
  public function testRegister()
  {
    $res = $this->withHeaders([
      'Accept' => 'application/json',
    ])->post('/api/register', [
      'firstName' => 'test2',
      'lastName' => 'test2',
      'email' => 'test1@test.test',
      'password' => 'test1234',
      'password_confirmation' => 'test1234'
    ]);

    print json_encode($res);
    $res->assertStatus(201);
  }

  public function test_updating_user()
  {
    $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
    $res = $this->withHeaders([
      'Accept' => 'application/json',
      'Content-Type' => 'application/x-www-form-urlencoded'
    ])->put('/api/users/edit/1', [
      'firstName' => 'testUpdatedFirst',
      'lastName' => 'testUpdatedLastName',
      'oldpassword' => 'test1234',
      //  'email' => 'test@test.test',
      'password' => 'test12345',
      'password_confirmation' => 'test12345'
    ]);

    print json_encode($res);
    $res->assertStatus(200);
  }

  // Test Login
  public function testLogin()
  {
    $res = $this->withHeaders([
      'Accept' => 'application/json',
    ])->post('/api/login', [
      'email' => 'test1@test.test',
      // Login with the updated password
      'password' => 'test12345',
    ]);

    print json_encode($res);
    $res->assertStatus(200);
  }

  // Test showing a user by id
  public function test_Find_User_By_Id()
  {
    $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
    $res = $this->withHeaders(['Accept' => 'application/json'])->get('/api/users/show/1');
    print json_encode($res);
    $res->assertStatus(200);
  }



  // Test Deleting User
  public function testDeletingUser()
  {
    $this->withoutMiddleware(\App\Http\Middleware\Authenticate::class);
    $res = $this->withHeaders(['Accept' => 'application/json'])->delete('/api/users/del/1');
    print json_encode($res);
    $res->assertStatus(200);
  }












}
