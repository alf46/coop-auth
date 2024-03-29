<?php class UserController
{
       private UserService $us;

       function __construct()
       {
              $this->us = new UserService();
       }

       public function Get($req, $res, array $args)
       {
              $id = $args["id"];
              $result = $this->us->Get($id);
              return json($res, $result);
       }

       public function Create($req, $res)
       {
              $body = $req->getParsedBody();

              $user = new UserData();
              $user->username = get($body, "username", true);
              $user->password = get($body, "password", true);
              $user->email = get($body, "email", false);
              $user->role = get($body, "role", true);

              $result = $this->us->Create($user);
              return json($res, $result, 201);
       }
}