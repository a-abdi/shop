<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PersonalInformationService;
use App\Contracts\Repositories\PersonalInformationRepositoryInterface;

class PersonalInformationController extends Controller
{
    public function __construct(
        private PersonalInformationRepositoryInterface $PersonalInformationRepository,
        private PersonalInformationService $personalInformationService,
    ){}

    /**
     * Create personal information for user.
     * If there is user information, it updates it.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(Request $request)
    {
        $this->personalInformationService->validateData($request->all());

        if ($request->image) {
            $request['image_src'] = $this->personalInformationService->storeFile(
                $request->image, 'profile'
            );
        }

        $userId = Auth::id();
        
        $personalInformation = $this->PersonalInformationRepository->updateOrCreate(
            [ 'user_id' => $userId ],
            $request->except('image'),
        );

        return $this->successResponse($personalInformation,  __('messages.updated', [
            'name' => 'information'
        ]));
    }
}
