<?php

class Controller_Authority extends Controller_Template
{

    public function action_login()
    {

        // create callback state info
        $state = serialize(array(
            'callback_url' => Input::get('callback_url'),
            'redirect_url' => Input::get('redirect_url'),
        ));

        // create login request for requested provider
        Auth_Opauth::forge(array('Strategy' => array(
            'Facebook' => array('state' => $state),
        )));

        // create view
        $view = View::forge('authority/index');
        // set template vars
        $this->template->title = 'Index';
        $this->template->content = $view;

    }

    public function action_callback()
    {

        try
        {

            ////////////////
            // LOGIN USER //
            ////////////////

            // get the Opauth object
            $opauth = Auth_Opauth::forge(false);
            // and process the callback
            $opauth->login_or_register();

            //////////////
            // REDIRECT //
            //////////////

            // get the oauth state
            $state = unserialize($opauth->get('auth.state'));
            // validate we have it
            if (!$state)
                throw new Exception('Callback State Missing');

            // restore session vars
            $callback_url = $state['callback_url'];
            $redirect_url = urlencode($state['redirect_url']);
            // get the logged in user id
            $user_id = Auth::instance()->get_user_id();
            // generate callback url with redirect
            $callback_url =  $callback_url . $user_id[1] . '?redirect_url=' . $redirect_url;
            // success, redirect
            return Response::redirect($callback_url, 'refresh');

        }
        catch (Exception $e)
        {

            // create error view
            $view = View::forge('authority/error');
            // set message
            $view->message = $e->getMessage();
            // set template vars
            $this->template->title = 'Index';
            $this->template->content = $view;

        }

    }

}
