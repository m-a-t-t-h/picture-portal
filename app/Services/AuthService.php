<?php

namespace App\Services;

use Auth;

class AuthService
{
    public static function isPublicEnforced()
    {
        switch (config("dkw.REQUIRE_PUBLIC_TAG")) {
            case "always":
                return TRUE;

            case "unauthed":
                if (!Auth::id()) return TRUE;
                break;
        }

        return FALSE;
    }
}
