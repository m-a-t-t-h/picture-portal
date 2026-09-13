<?php namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public static function isPublicEnforced()
    {
        switch (config("dkw.REQUIRE_PUBLIC_TAG")) {
            case "always":case 1:
                return TRUE;

            case "unauthed":
                if (!Auth::id()) return TRUE;
                break;
        }

        return FALSE;
    }

    public static function isMp3Mp4DirectAccessEnabled(): bool
    {
        $config = config("dkw.DIRECT_ACCESS_TO_MP3_MP4");
        if ($config === FALSE) return FALSE;

        if ($config === "authed") {
            if (Auth::id()) return TRUE;

            return FALSE;
        }

        if ($config === TRUE) {
            return TRUE;
        }

        return FALSE;

    }
}
