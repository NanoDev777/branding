<?php

namespace App\Http\Controllers\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\User;

class CustomAccessTokenController extends AccessTokenController
{
	public function issueUserToken(ServerRequestInterface $request) {   
        $httpRequest = request();
        if($httpRequest->grant_type == 'password') {
            $user = User::where('email', $httpRequest->username)
                ->where('active', 1)
                ->first();
            if($user == NULL) {
                return response()->json([
                    'status' => 'negative',
                    'message' => message('MSG012')
                ]);
            }
            foreach ($user->profile->actions as $action) {
                if(strpos($action->method, '|') !== false) {
                    $pipe = explode('|', $action->method);
                    $actions[] = $pipe[0];
                    $actions[] = $pipe[1];
                } else {
                    $actions[] = $action->method;
                }
            }
            Cache::add('actions_' .  $user->id, $actions, 120);
            return $this->issueToken($request);
        }
    }
}
