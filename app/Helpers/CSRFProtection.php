<?php

namespace App\Helpers;

use App\Helpers\Str;

class CSRFProtection {
    public static function token()
    {
        $csrfToken = Str::token();
        $_SESSION['csrfToken'] = $csrfToken;

        return $csrfToken;
    }
}
