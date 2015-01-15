<?php

class ApiController extends BaseController {

    public function __construct()
    {
        // Skip CSRF filter
    }

    public function getMe()
    {
        $user = User::find(ResourceServer::getOwnerId());

        return [
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'admin' => (bool) $user->is_admin,
            'description' => $user->extras,
        ];
    }

}
