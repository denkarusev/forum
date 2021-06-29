<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterFormRequest;
use App\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * @param RegisterFormRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterFormRequest $request): JsonResponse
    {
        $user = User::create(array_merge(
            $request->only('name', 'email'),
            ['password' => bcrypt($request->password)],
        ));

        return response()->json([
            'message' => 'Вы успешно зарегистрировались. Для входа используйте адрес электронной почты и пароль'
        ]);
    }
}
