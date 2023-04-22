<?php interface IUserService
{
    public function Create(UserData $request): UserDetailResponse;
    public function Get($username, $onlyEnabled = false): UserDetailResponse;
    public function GetWithPassword($username): UserData;
    public function UpdatePassword($username,$password);
}