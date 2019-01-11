<?php

namespace App\Http\Controllers;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider ($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback ($provider)
    {
        $socialuser = Socialite::driver($provider)->user();

        $user = User::where('email', $socialuser->email)->first();

        if (!$user) {
            $user = new User();
            $user->username = preg_replace('/\s/', '', $socialuser->name) . '_' . str_random(5);
            $user->email = $socialuser->email;
            $user->password = str_random(10);
            $user->full_name = $socialuser->name;
            $user->gender = $socialuser->user['gender'];
            $user->save();
        }

        $smt = $user->socialmediatoken;

        switch ($provider) {
            case 'facebook':
                $smt->facebook_token = $socialuser->token;
                break;
            case 'google':
                $smt->google_token = $socialuser->token;
                break;
            default:

        }

        $smt->save();

        Auth::login($user);

        return redirect()->route('main');
    }
}
