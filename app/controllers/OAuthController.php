<?php

class OAuthController extends BaseController {

    public function __construct()
    {
        // Skip CSRF filter
    }

    public function postAccessToken()
    {
        return AuthorizationServer::performAccessTokenFlow();
    }

    public function getAuthorize()
    {
        // get the data from the check-authorization-params filter
        $params = Session::get('authorize-params');

        // get the user id
        $params['user_id'] = Auth::user()->id;

        $code = AuthorizationServer::newAuthorizeRequest('user', Auth::user()->id, $params);

        Session::forget('authorize-params');

        return Redirect::to(AuthorizationServer::makeRedirectWithCode($code, $params));
    }

}
