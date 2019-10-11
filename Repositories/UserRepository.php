<?php

namespace Innerent\People\Repositories;

use Innerent\Foundation\Repositories\Repository;
use Innerent\People\Contracts\User as UserRepoContract;
use Innerent\People\Models\User;

class UserRepository extends Repository implements UserRepoContract
{
    function __construct(User $model)
    {
        parent::__construct($model);
    }
}
