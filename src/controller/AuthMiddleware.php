<?php
class AuthMiddleware
{
    private $firebase;

    public function __construct($f)
    {
        $this->firebase = $f;
    }


    public function __invoke($request, $response, $next)
    {
        if ($request->hasHeader('Authorization')) {
            // get the header value
            $headerValue = $request->getHeader('Authorization');
            
            $jwtToken = explode(" ", $headerValue[0])[1];

            //verify the jwt retrieve from client app
            $verifiedIdToken = $this->firebase->getAuth()->verifyIdToken($jwtToken);

            // get the 'uid' from the token
            // get the currently signed-in user from the 'uid'

            $response = $next($request, $response);
        }
        else {
             // no jwt token found
            $response = $response->withJson(array(
                'success' => false,
                'msg' => "Accessied denied. Token not found"
            ), 401);
        }
        
        return $response;
    }
}