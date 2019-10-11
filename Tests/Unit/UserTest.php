<?php

namespace Innerent\People\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Innerent\Foundation\Models\LegalDocument;
use Innerent\People\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    private $authUser;

    function setUp(): void
    {
        parent::setUp();

        $this->authUser = factory(User::class)->create();
    }

    public function testCreateUser()
    {
        $data = factory(User::class)->make()->toArray();

        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';

        $data['roles'] = [1, 2];

        $data['documents'] = factory(LegalDocument::class, 2)->make()->toArray();

        unset($data['email_verified_at']);

        $response = $this->actingAs($this->authUser, 'api')->json('post', config('foundation.api.prefix').'/users', $data);

        $response->assertStatus(201);
    }

    public function testListUsers()
    {
        $this->actingAs($this->authUser, 'api')->json('get', config('foundation.api.prefix').'/users')->assertStatus(200);
    }

    public function testShowUser()
    {
        $this->actingAs($this->authUser, 'api')->json('get', config('foundation.api.prefix').'/users/' . $this->authUser->uuid)->assertStatus(200);
    }

    public function testUpdateUser()
    {
        $newData = factory(User::class)->create()->toArray();

        unset($newData['email_verified_at']);

        $newData['uuid'] = $this->authUser->uuid;
        $newData['email'] = $newData['email'] . 'sd';

        unset($newData['created_at']);

        $response = $this->actingAs($this->authUser, 'api')->json('put', config('foundation.api.prefix').'/users/' . $this->authUser->uuid, $newData);

        $response->assertJsonFragment($newData)->assertStatus(200);
    }

    public function testDeleteUser()
    {
        $this->actingAs($this->authUser, 'api')
            ->json('delete', config('foundation.api.prefix').'/users/' . $this->authUser->uuid)
            ->assertStatus(204);
    }
}
