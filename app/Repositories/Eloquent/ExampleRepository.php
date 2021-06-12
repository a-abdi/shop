<?php

namespace App\Repositories\Eloquent;

use App\Models\Example;
use App\Contracts\Repositories\ExampleRepositoryInterface;

class ExampleRepository extends BaseRepository implements ExampleRepositoryInterface
{
    public function __construct(private Example $example) 
    {
        parent::__construct($example);
    }
}
