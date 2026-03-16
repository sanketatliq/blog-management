<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\LoginResource;
use App\Models\User;
use App\Traits\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponseHelper;

    // Login User
    public function login(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email'    => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->messages()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Attempt authentication
            if (! Auth::attempt($request->only('email', 'password'))) {
                return $this->sendError('Invalid email or password.', Response::HTTP_UNAUTHORIZED);
            }

            $user  = Auth::user();
            $user['accessToken'] = $user->createToken('authtoken')->plainTextToken;

            return $this->sendResponse('User verified successfully.', new LoginResource($user));
        } catch (\Throwable $th) {
            Log::error('Login failed', [$th->getMessage()]);

            return $this->sendError('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR, $th);
        }
    }
}
