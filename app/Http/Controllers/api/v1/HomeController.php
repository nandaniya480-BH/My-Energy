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
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();

            $user = $this->userRepository->createUser($request->validated());

            if ($user) {
                event(new Registered($user));
                DB::commit();
                return $this->success(trans('message.registered'), $user, Response::HTTP_OK);
            }

            DB::commit();
            return $this->error(trans('message.inCorrectCredentials'), Response::HTTP_BAD_REQUEST);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }


    public function SignIn(LoginApiRequest $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
                $user = $this->userRepository->getUserData();
                $user['access_token'] = $user->createToken('MyAuthApp')->plainTextToken;

                $this->userRepository->updateUser($user->id, ["access_token" => $user['access_token']]);

                return $this->success(trans('message.loginSuccessfully'), ['access_token' => $user->access_token], Response::HTTP_OK);
            } elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 0])) {
                return $this->error(trans('message.custom.account_verify'), Response::HTTP_BAD_REQUEST);
            } else {
                return $this->error(trans('message.inCorrectCredentials'), Response::HTTP_BAD_REQUEST);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function changePassword(changePasswordRequest $request)
    {
        try {
            $user = $this->userRepository->getUserData();
            if (Hash::check($request->password, $user->password)) {
                $user['access_token'] = $user->createToken('MyAuthApp')->plainTextToken;
                $this->userRepository->updateUser($user->id, ["password" => $request->password]);
                return $this->success(trans('message.custom.update_messages', ["attribute" => "Password"]), [], Response::HTTP_OK);
            } else {
                return $this->error(trans('message.inCorrectCredentials'), Response::HTTP_BAD_REQUEST);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function logOut()
    {
        try {
            $user = $this->userRepository->getUserData();
            $user->tokens()->delete();
            $update = $this->userRepository->logout($user->id);
            if ($update) {
                return $this->success(trans('message.logout'), [], Response::HTTP_OK);
            } else {
                return $this->error(trans('validation.unknown_error'), Response::HTTP_BAD_REQUEST);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getUser(Request $request)
    {
        try {
            $data = $this->userRepository->getUserData($request);
            return $this->success(trans('message.user_detail'),  $data, Response::HTTP_OK);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function forgotPassword(ForgotPasswordApiRequest $request)
    {
        try {
            $status = Password::sendResetLink($request->only('email'));
            if ($status == Password::RESET_LINK_SENT) {
                return $this->success(__($status),  [], Response::HTTP_OK);
            }
            return $this->error(__($status), Response::HTTP_BAD_REQUEST);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function resetPassword(ResetPasswordApiRequest $request)
    {
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'confirm_password', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                return $this->success(__($status), [], Response::HTTP_OK);
            }
            return $this->error(__($status), Response::HTTP_BAD_REQUEST);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function verify(Request $request)
    {
        try {
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
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
