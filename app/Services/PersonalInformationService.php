<?php

namespace App\Services;

use App\Services\MainService;
use Illuminate\Validation\Rule;

class PersonalInformationService extends MainService
{
    /**
     * Validate personal information data.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateData(array $data)
    {
        $rule = [
            'phone_number' => 'nullable|string|min:10|max:12',
            'address'      => 'nullable|string|min:10',
            'gender'       => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'birthday'     => 'nullable|date',
            'image'        => 'nullable|file|image|max:512',
        ];

        $this->validate($data, $rule);

        return true;
    }
}