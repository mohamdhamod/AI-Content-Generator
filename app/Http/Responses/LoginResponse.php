<?php

namespace App\Http\Responses;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function toResponse($request)
    {
        $locale = app()->getLocale();

        if (auth()->user()->hasRole(RoleEnum::ADMIN)) {
            return redirect()->intended(route('dashboard', ['locale' => $locale]));
        }

        return redirect()->intended(route('home', ['locale' => $locale]));
    }
}
