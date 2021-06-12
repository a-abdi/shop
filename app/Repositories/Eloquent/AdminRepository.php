<?php

namespace App\Repositories\Eloquent;

use App\Models\Admin;
use App\Contracts\Repositories\AdminRepositoryInterface;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    public function __construct(private Admin $admin) 
    {
        parent::__construct($admin);
    }
}
