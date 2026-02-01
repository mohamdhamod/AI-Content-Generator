<?php

namespace App\Actions\Fortify;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use FileHandler;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            'registration_token' => ['required', 'string', 'min:10'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'term_and_policy' => ['required', 'accepted'],
            'phone' => ['nullable', 'string', 'max:20'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {

            $email = strtolower((string) $input['email']);
            $tokenHash = hash('sha256', (string) $input['registration_token']);

            $link = DB::table('registration_links')
                ->where('email', $email)
                ->where('token_hash', $tokenHash)
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            if (!$link) {
                throw ValidationException::withMessages([
                    'email' => [__('translation.auth.continue_registration_invalid_or_expired')],
                ]);
            }

            $googleRegistration = Session::get('google_registration');
            $googleId = null;
            if (is_array($googleRegistration)
                && isset($googleRegistration['email'])
                && strtolower((string) $googleRegistration['email']) === $email
                && isset($googleRegistration['google_id'])
                && is_string($googleRegistration['google_id'])
                && $googleRegistration['google_id'] !== ''
            ) {
                $googleId = $googleRegistration['google_id'];
            }

            // Generate random password
            $randomPassword = Str::random(16);

            $user = User::create([
                'name' => $input['name'],
                'email' => $email,
                'phone' => $input['phone'] ?? null,
                'country_id' => $input['country_id'],
                'password' => $randomPassword,
                'term_and_policy'=> $input['term_and_policy'] ?? 0,
                'email_verified_at' => now(),
                'google_id' => $googleId,
                'locale_auto_detected' => false,
            ]);
            $user->assignRole(RoleEnum::SUBSCRIBER);

            DB::table('registration_links')
                ->where('email', $email)
                ->where('token_hash', $tokenHash)
                ->delete();

            if ($googleId !== null) {
                Session::forget('google_registration');
            }

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
