<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Arr;

class UserService
{
    public function createOrFindUserIfExist($data, $mode = null, $referral = false)
    {
        $email = strtolower($data['email']);

        $userEmail = $this->find($email);

        $generatedPassword = $this->hashing();

        return $userEmail ? $userEmail :
            User::create(
                array_merge(
                    Arr::only($data, ['first_name', 'last_name', 'phone']),
                    [
                        'email' => $email,
                        'role' => 'User',
                        'user_access_code_counter' => 1,
                        'registration_mode' => $mode,
                        'system_verification' => true,
                        'referral_id' => $referral ? auth('api')->id() : null,
                        'isset_password' => true,
                    ]
                )
            );
    }

    public function findUserRegistrationOutsideAuth($data, $mode = null)
    {
        $email = strtolower($data['email']);

        // return
        //     optional($this->find($email))->registration_mode == $mode && optional($this->find($email))->user_access_code != null
        //         ? $this->updateOutsideAuth($data, $mode)
        //         : $this->createOrFindUserIfExist($data, $mode, false);

        return $this->find($email)?->isset_password == true
                    ? $this->updateOutsideAuth($data, $mode)
                    : $this->createOrFindUserIfExist($data, $mode, false);
    }

    public function updateOutsideAuth($data, $mode = null)
    {
        $email = strtolower($data['email']);

        $generatedPassword = $this->hashing();

        return $this->find($email)->update([
            'email' => $email,
            'user_access_code' => $generatedPassword,
            'user_access_code_counter' => $this->find($email)->user_access_code_counter + 1,
        ]) ? $this->find($email) : null;
    }

    public function updateOrCreateIfExist($data, $mode = null, $referral = false)
    {
        $generatedPassword = $this->hashing();

        $email = strtolower($data['email']);

        $user = $this->find($email) ? ($this->update($data) ? $this->find($email) : null)
            : User::create(
                array_merge(
                    Arr::only($data, ['first_name', 'last_name', 'phone']),
                    [
                        'email' => $email,
                        'role' => 'User',
                        'user_access_code_counter' => 1,
                        'registration_mode' => $mode,
                        'system_verification' => true,
                        'referral_id' => $referral ? auth('api')->id() : null,
                    ]
                )
            );

        return $user;
    }

    public function find($email)
    {
        return User::where('email', $email)->first();
    }

    public function createUserParams(array $data)
    {
        return User::create($data);
    }

    public function updateUserParams(User $user, $data)
    {
        $user->create($data);

        return $user;
    }

    public function update($data)
    {
        $email = strtolower($data['email']);

        return $this->find($email)
                ? $this->find($email)->update([
                    'email' => $email,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                ]) : null;
    }

    public function userPropertyById($id)
    {
        return User::with(
            'teams',
            'prints',
            'company',
            'teams.subscription',
            'teams.subscription.plan',
            'teams.subscription.plan.features',
        )->find($id);
    }

    public function userPropertyTeams($id)
    {
        return User::with(
            'teams.users',
            'company',
            'teams.subscription',
            'teams.subscription.plan',
            'teams.subscription.plan.features',
        )->find($id)->teams;
    }

    public function userTeamUsers()
    {
        return User::with(
            'teams.users',
            'teams.deletedUsers',
            'teams.subscription',
            'teams.subscription.plan',
            'teams.subscription.plan.features',
        )->find(auth('api')->id())->teams;
    }

    public function userTeamUsersById($id)
    {
        return User::with(
            'teams.users',
            'teams.deletedUsers',
            'teams.subscription',
            'teams.subscription.plan',
            'teams.subscription.plan.features',
        )->find($id)->teams;
    }

    public function hashing()
    {
        $permitted_chars = '0123456789';

        return substr(str_shuffle($permitted_chars), 0, 6);
    }

    public function toLowerCase($email)
    {
        return strtolower($email);
    }

    public function thirdPartyParticipants($email)
    {
        return User::where('email', $email)->where('isset_password', false)->first();
    }
}
