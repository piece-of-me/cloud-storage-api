<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Вывод информации пользователя
     *
     * @return UserResource
     */
    public function show(): UserResource
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    /**
     * Обновление информации пользователя
     *
     * Ожидаемые значения:
     * password
     * email
     * phone
     * name
     * surname
     * birthdate
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function update(UserRequest $request): UserResource
    {
        $data = $request->validated();
        $user = auth()->user();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return new UserResource($user);

    }
}
