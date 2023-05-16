<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function createUser($request)
    {
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request);
        return $user;
    }

    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function findUserById($id)
    {
        return User::find($id);
    }

    public function updateUser($id, $data)
    {
        $user = $this->findUserById($id);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }

    public function logout($userId)
    {
        return User::where('id', $userId)->update(array('updated_at' => now()));
    }

    public function getUserData()
    {
        return Auth::user();
    }
}
