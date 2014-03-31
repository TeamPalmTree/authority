<?php

class Controller_Authority extends Controller_Standard
{

    public function action_login()
    {

        // create callback state info
        $state = serialize(array(
            'callback_url' => Input::get('callback_url'),
            'redirect_url' => Input::get('redirect_url'),
        ));

        // create login request for requested provider
        Authority_Opauth::forge(array('Strategy' => array(
            'Facebook' => array('state' => $state),
        )));

    }

    public function action_callback()
    {

        try
        {

            ////////////////
            // LOGIN USER //
            ////////////////

            // get the Opauth object
            $opauth = Authority_Opauth::forge(false);
            // and process the callback
            $user_id = $opauth->get_or_create();

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
            // generate callback url with redirect
            $callback_url =  $callback_url . '/' . $user_id . '?redirect_url=' . $redirect_url;
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
            $this->template->section->body = $view;

        }

    }

}
