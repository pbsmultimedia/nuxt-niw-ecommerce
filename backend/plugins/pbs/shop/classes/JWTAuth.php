<?php

namespace Pbs\Shop\Classes;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Winter\User\Models\User;
use Config;

class JWTAuth
{
    public static function generateToken(User $user)
    {
        $key = self::getSecretKey();
        $payload = [
            'iss' => Config::get('app.url'),
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // 1 week
            'sub' => $user->id,
            'email' => $user->email
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function decodeToken($token)
    {
        try {
            $key = self::getSecretKey();
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    protected static function getSecretKey()
    {
        return env('JWT_SECRET', env('APP_KEY'));
    }
}
