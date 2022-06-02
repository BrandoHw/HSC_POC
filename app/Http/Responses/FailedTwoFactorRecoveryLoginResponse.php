<?php

namespace App\Http\Responses;

use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;

class FailedTwoFactorRecoveryLoginResponse implements FailedTwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $message = __('The provided two factor authentication code was invalid.');
        $message2 = __('The provided two factor recovery code was invalid.');

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'code' => [$message],
                'recovery' => true,
            ]);
        }

        if ($request->code === "RECOVERY"){
            return redirect()->route('two-factor.login')->withErrors([
                'recovery_code' => $message2,
                'recovery' => "RECOVERY",
            ]);
        }
        return redirect()->route('two-factor.login')->withErrors([
            'code' => $message,
            'email' => $message,
        ]);
    }
}
