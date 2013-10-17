<?php

class Controller_Authority extends Controller_Hybrid
{

    public function before()
    {
        $this->section = 'contribute';
        parent::before();
    }

    public function action_login($provider, $redirect)
    {
        // create login request for requested provider
        Auth_Opauth::forge(array('provider' => $provider));
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
            // get the Opauth object
            $opauth = Auth_Opauth::forge(false);
            // and process the callback
            $opauth->login_or_register();
            // success, redirect

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
