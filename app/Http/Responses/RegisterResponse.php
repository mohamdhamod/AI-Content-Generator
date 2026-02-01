<?php

namespace App\Http\Responses;

use App\Enums\RoleEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
class RegisterResponse implements RegisterResponseContract
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

        $redirectUrl = auth()->user()->hasRole(RoleEnum::ADMIN)
            ? route('dashboard', ['locale' => $locale])
            : route('home', ['locale' => $locale]);

        // If AJAX / expects JSON, return a JSON payload with redirect
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return new JsonResponse(['message' => __('translation.messages.registered'), 'redirect' => $redirectUrl], 200);
        }

        return redirect()->intended($redirectUrl);
    }
}
