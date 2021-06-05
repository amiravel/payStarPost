<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Response\Response;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $normalUser = Role::query()->whereTitle('normal user')->first();

        $user = User::query()->create([
            'role_id' => $normalUser->id,
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        return Response::json(200, new UserResource($user));
    }

}
