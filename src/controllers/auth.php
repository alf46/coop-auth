<?php class AuthController
{
       private AuthService $as;

       function __construct()
       {
              $this->as = new AuthService();
       }

       public function Login($req, $res)
       {
              $body = $req->getParsedBody();
              $username = get($body, "username", true);
              $password = get($body, "password", true);
              $result = $this->as->Login($username, $password);
              return json($res, $result);
       }

       public function Forgot($req, $res)
       {
              $body = $req->getParsedBody();
              $username = get($body, "username", true);
              $result = $this->as->Forgot($username);
              return json($res, $result);
       }

       public function Recovery($req, $res)
       {
              $body = $req->getParsedBody();
              $code = get($body, "code", true);
              $password = get($body, "password", true);
              $this->as->Recovery($code, $password);
              return json($res, null);
       }

       public function ChangePassword($req, $res)
       {
              $body = $req->getParsedBody();
              // $password = get($body, "password", true);
              // $this->as->Recovery($code, $password);
              return json($res, null);
       }

       public function Info($req, $res)
       {
              $username = $_SESSION['sub'];
              $email = $_SESSION['email'];
              $role = $_SESSION['role']; // adm, socio
              return json($res, array("username" => $username, "email" => $email, "role" => $role));
       }
}