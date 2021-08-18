<?php

namespace App\Repositories\Eloquent;

use App\Models\PersonalInformation;
use App\Contracts\Repositories\PersonalInformationRepositoryInterface;

class PersonalInformationRepository extends BaseRepository implements PersonalInformationRepositoryInterface
{
    public function __construct(private PersonalInformation $personalinformation) 
    {
        parent::__construct($personalinformation);
    }
}
