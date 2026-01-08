<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
//        $request->user()->tokens()->delete(); revoga todos os tokens do usuário, usado quando quiser manter 1 sessão ativa, troca de senha ou logout global
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
