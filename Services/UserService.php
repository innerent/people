<?php

namespace Innerent\People\Services;

use Illuminate\Support\Facades\DB;
use Innerent\Foundation\Services\Service;
use Innerent\People\Contracts\User as UserRepoContract;
use Innerent\People\Models\User;

class UserService extends Service
{
    function __construct(UserRepoContract $repo)
    {
        parent::__construct($repo);
    }

    public function make(array $data)
    {
        DB::beginTransaction();

        $user = $this->repo->make($data)->toModel();

        if (isset($data['roles']))
            $user->roles()->sync($data['roles']);

        if (isset($data['legalDocuments'])) {
            foreach ($data['legalDocuments'] as $document) {
                $user->legalDocuments()->create($document);
            }
        }

        DB::commit();

        if (isset($data['must_verify_email']))
            $user->sendEmailVerificationNotification();

        return $user->load('roles');
    }

    public function get($id): User
    {
        return $this->repo->get($id)->toModel()->load(['roles.permissions']);
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();

        $user = $this->repo->get($id)->update($data)->toModel();

        if (isset($data['roles']))
            $user->roles()->sync($data['roles']);

        if (isset($data['legalDocuments'])) {
            $user->legalDocuments()->delete();

            foreach ($data['legalDocuments'] as $document) {
                $user->legalDocuments()->create($document);
            }
        }

        DB::commit();

        return $user->load('roles', 'legalDocuments');
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
