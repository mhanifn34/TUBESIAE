<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // <-- 1. IMPORT "OTAK BESAR"

class Controller extends BaseController // <-- 2. SAMBUNGIN "BATANG OTAK" KE "OTAK BESAR"
{
    use AuthorizesRequests, ValidatesRequests; // <-- Ini bonus biar bisa pake $this->authorize()
}