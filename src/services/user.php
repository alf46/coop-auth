<?php class UserService implements IUserService
{
    public function Create(UserData $user): UserDetailResponse
    {
        global $db;

        $hasher = new PasswordHasher();
        $hash = $hasher->hashPassword($user->password);

        $query = "INSERT INTO `user` (`username`, `password`,`email`, `role`) VALUES (?,?,?,?)";
        $result = $db->Execute($query, array($user->username, $hash, $user->email, $user->role), true);

        if ($result) {
            return $this->Get($user->username);
        }

        throw new Exception("error.post.user", 500);
    }

    public function Get($id, $onlyEnabled = false): UserDetailResponse
    {
        global $db;
        $query = "SELECT `username`, `email`, `role`, `enabled`, `created_at`, `updated_at` FROM `user` WHERE `username` = ?";
        if ($onlyEnabled) {
            $query .= " AND `enabled` = 1";
        }

        $db->Execute($query, array($id));
        if ($db->NumRows() > 0) {
            $data = $db->FetchRow();
            $user = new UserDetailResponse();
            $user->username = $data["username"];
            $user->email = $data["email"];
            $user->role = $data["role"];
            $user->created_at = $data["created_at"];
            $user->updated_at = $data["updated_at"];
            $user->enabled = $data["enabled"];
            return $user;
        }

        throw new Exception("user." . $id . ".not.found", 404);
    }

    public function GetWithPassword($id): UserData
    {
        global $db;
        $query = "SELECT `username`, `password`, `email`, `role` FROM `user` WHERE `username` = ? AND `enabled` = 1";
        $db->Execute($query, array($id));
        if ($db->NumRows() > 0) {
            $data = $db->FetchRow();
            $user = new UserData();
            $user->username = $data["username"];
            $user->password = $data["password"];
            $user->email = $data["email"];
            $user->role = $data["role"];
            return $user;
        }
        throw new Exception("login.failed", 401);
    }

    public function UpdatePassword($username, $password)
    {
        global $db;
        $hasher = new PasswordHasher();
        $hash = $hasher->hashPassword($password);

        $query = "UPDATE `user` SET `password`=? WHERE `username`=?";
        $result = $db->Execute($query, array($hash, $username));

        if (!$result) {
            throw new Exception("error.update.user", 500);
        }
    }
}