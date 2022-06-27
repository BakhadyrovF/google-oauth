<?php

namespace App\Http\Guards;

use App\Models\GoogleToken;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;

class Google implements Guard
{

    protected $user;
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return (bool)$this->user();
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->user();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!$this->user) {

            $this->setUser(GoogleToken::query()
                ->where('token', '=', decrypt_cookie(\request()->cookie('access_token')))
                ->first()?->user
            );

        }

        return $this->user;

    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id()
    {
        return $this->user()->id ?? null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }

    /**
     * Set the current user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    public function setUser(?Authenticatable $user)
    {
        $this->user = $user;
    }
}
