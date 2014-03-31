<?php

class Authority_Opauth extends \Auth\Auth_Opauth
{

    public function get_or_create()
    {

        //////////////////
        // RUN CALLBACK //
        //////////////////

        // process the callback data
        $this->callback();
        // if there is no UID we don't know who this is
        if ($this->get('auth.uid', null) === null)
            throw new \OpauthException('No uid in response from the provider, so we have no idea who you are.');

        ////////////////////////////////////////
        // CHECK FOR EXISTING UID ASSOCIATION //
        ////////////////////////////////////////

        // the user exists, so send him on his merry way as a user
        if ($authentication = \DB::select()->from($this->config['table'])->where('uid', '=', $this->get('auth.uid'))->where('provider', '=', $this->get('auth.provider'))->as_object()->execute() and $authentication->count())
        {
            // force a login with this username
            $authentication = $authentication->current();
            return (int)$authentication->parent_id;
        }

        //////////////////////////////////////////
        // CHECK FOR EXISTING EMAIL OR USERNAME //
        //////////////////////////////////////////

        // check if we already have an account with this email address or username
        $existing = \Auth\Model\Auth_User::query()
            ->where('username', '=', $this->response['auth']['info']['nickname'])
            ->or_where('email', '=', $this->response['auth']['info']['email'])
            ->get_one();

        // see if we have an existing user
        if ($existing)
        {

            // attach this authentication to the EXISTING user
            $insert_id = $this->link_provider(array(
                'parent_id'		=> $existing->id,
                'provider' 		=> $this->get('auth.provider'),
                'uid' 			=> $this->get('auth.uid'),
                'access_token' 	=> $this->get('auth.credentials.token', null),
                'secret' 		=> $this->get('auth.credentials.secret', null),
                'expires' 		=> $this->get('auth.credentials.expires', null),
                'refresh_token' => $this->get('auth.credentials.refresh_token', null),
                'created_at' 	=> time(),
            ));

            // success
            if ($insert_id)
                return (int)$existing->id;

            // fail
            throw new \OpauthException('Found existing user, but unable to link to provider');

        }

        ///////////////////////////////////
        // CREATE AND ASSOCIATE NEW USER //
        ///////////////////////////////////

        // generate a dummy password if we don't have one, and want auto registration for this user
        if ($this->config['auto_registration'])
            $this->get('auth.info.password') or $this->response['auth']['info']['password'] = \Str::random('sha1');

        // did the provider return enough information to log the user in?
        if (($this->get('auth.info.nickname') or $this->get('auth.info.email')) and $this->get('auth.info.password'))
        {

            // make sure we have a nickname, if not, use the email address
            if (empty($this->response['auth']['info']['nickname']))
                $this->response['auth']['info']['nickname'] = $this->response['auth']['info']['email'];

            // make a user with what we have
            $user_id = $this->create_user($this->response['auth']['info']);

            // attach this authentication to the new user
            $insert_id = $this->link_provider(array(
                'parent_id'		=> $user_id,
                'provider' 		=> $this->get('auth.provider'),
                'uid' 			=> $this->get('auth.uid'),
                'access_token' 	=> $this->get('auth.credentials.token', null),
                'secret' 		=> $this->get('auth.credentials.secret', null),
                'expires' 		=> $this->get('auth.credentials.expires', null),
                'refresh_token' => $this->get('auth.credentials.refresh_token', null),
                'created_at' 	=> time(),
            ));

            // force a login with this users id
            if ($insert_id and \Auth::instance()->force_login((int)$user_id))
                return (int)$user_id;

            // fail
            throw new \OpauthException('We tried automatically creating a user but that just really did not work. Not sure why...');

        }

        // fail
        throw new \OpauthException('Not enough information to create user');

    }

}