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
            $redirectUrl = route('dashboard', ['locale' => $locale]);
        } else {
            $redirectUrl = route('home', ['locale' => $locale]);
        }

        // For AJAX requests, return JSON with redirect URL
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.auth.login_success'),
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->intended($redirectUrl);
    }
}
