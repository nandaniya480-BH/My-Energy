<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\changePasswordRequest;
use App\Http\Requests\ForgotPasswordApiRequest;
use App\Http\Requests\LoginApiRequest;
use App\Http\Requests\RegisterApiRequest;
use App\Http\Requests\ResetPasswordApiRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Helpers\Helper;

class HomeController extends Controller
{
    use ApiResponseTrait;
    use HasApiTokens;

    protected $userRepository = '';
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signUp(RegisterApiRequest $request)
    {
        $user = $this->userRepository->createUser($request->validated());
        if ($user) {
            event(new Registered($user));
            return $this->sendResponse(trans('message.registered'), true, array($user), Response::HTTP_OK);
        }
        return $this->sendError(trans('message.inCorrectCredentials'), null, Response::HTTP_BAD_REQUEST);
    }

    public function SignIn(LoginApiRequest $request)
    {
        if (Auth::attempt(array('email' => $request->email, 'password' => $request->password, 'status' => 1))) {

            $user = $this->userRepository->getUserData();
            $user['access_token'] = $user->createToken('MyAuthApp')->plainTextToken;

            $this->userRepository->updateUser($user->id, ["access_token" => $user['access_token']]);
            return $this->sendResponse(trans('message.loginSuccessfully'), true, array('access_token' => $user->access_token), Response::HTTP_OK);
        } elseif (Auth::attempt(array('email' => $request->email, 'password' => $request->password, 'status' => 0))) {
            return $this->sendError(trans('message.custom.account_verify'), null, Response::HTTP_BAD_REQUEST);
        } else {
            return $this->sendError(trans('message.inCorrectCredentials'), null, Response::HTTP_BAD_REQUEST);
        }
    }

    public function changePassword(changePasswordRequest $request)
    {
        $user = $this->userRepository->getUserData();
        if (Hash::check($user->password, $request->password)) {
            $user['access_token'] = $user->createToken('MyAuthApp')->plainTextToken;
            $this->userRepository->updateUser($user->id, ["password" => $request->password]);
            return $this->sendResponse(trans('message.custom.update_messages', ["attribute" => "Password"]), true, [], Response::HTTP_OK);
        } else {
            return $this->sendError(trans('message.inCorrectCredentials'), null, Response::HTTP_BAD_REQUEST);
        }
    }

    public function logOut()
    {
        $user = $this->userRepository->getUserData();
        $user->tokens()->delete();
        $update = $this->userRepository->logout($user->id);
        if ($update) {
            return $this->sendResponse(trans('message.logout'), true, null, Response::HTTP_OK);
        } else {
            return $this->sendError(trans('validation.unknown_error'), null, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getUser(Request $request)
    {
        $data = $this->userRepository->getUserData($request);
        return $this->sendResponse(trans('message.user_detail'), true, $data, Response::HTTP_OK);
    }

    public function forgotPassword(ForgotPasswordApiRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return $this->sendResponse(__($status), true, null, Response::HTTP_OK);
        }
        return $this->sendError(__($status), null, Response::HTTP_BAD_REQUEST);
    }

    public function resetPassword(ResetPasswordApiRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'confirm_password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->sendResponse(__($status), true, null, Response::HTTP_OK);
        }
        return $this->sendError(__($status), null, Response::HTTP_BAD_REQUEST);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'hash' => 'required',
        ]);
        $user = $this->userRepository->findUserById($request->id);

        if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
            abort(404);
        }

        if ($user->hasVerifiedEmail()) {
            $message = trans('message.already_mail_verified');
            return view('email_verify_thank_u', compact('message'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        $message = trans('message.mail_verified');
        return view('email_verify_thank_u', compact('message'));
    }

    public function test()
    {
        Helper::TwilioMessage("+919081931001", "hii Govind");
    }
}
