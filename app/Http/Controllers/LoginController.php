<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Response\Response;


class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        /**
         * @var User $user
         */
        $user = User::query()->whereEmail($request->get('email'))
            ->firstOrFail();

        $token = $user->authenticate();

        return Response::json(200, [
            'token' => $token,
            'client_id' => $user->client_id
        ]);
    }
}
