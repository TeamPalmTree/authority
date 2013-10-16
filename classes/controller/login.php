<?php

class Controller_Authority extends Controller_TeamPalmTree
{

    public function before()
    {
        $this->section = 'contribute';
        parent::before();
    }

    public function action_index()
    {
        // create view
        $view = View::forge('contribute/index');
        // set template vars
        $this->template->title = 'Index';
        $this->template->content = $view;
    }

    /**
     * eg. http://www.exemple.org/auth/login/facebook/ will call the facebook opauth strategy.
     * Check if $provider is a supported strategy.
     */
    public function action_login()
    {
        \Config::load('auth', true);
        $opauth = \Auth_Opauth::forge(array('provider' => 'Facebook'));
        // create view
        $view = View::forge('welcome/index');
        // set template vars
        $this->template->title = 'Index';
        $this->template->content = $view;
    }

    public function action_callback()
    {
        $view = View::forge('welcome/index');
        $view->opauth = '';
        $view->message = '';
        // Opauth can throw all kinds of nasty bits, so be prepared
        try
        {
            // get the Opauth object
            $opauth = \Auth_Opauth::forge(false);

            // and process the callback
            $status = $opauth->login_or_register();
            $view->opauth = $opauth;
            // set template vars
            $this->template->title = 'Index';
            $this->template->content = $view;

        }

            // deal with Opauth exceptions
        catch (\OpauthException $e)
        {

            $view->message = $e->getMessage();

        }

            // catch a user cancelling the authentication attempt (some providers allow that)
        catch (\OpauthCancelException $e)
        {
            // you should probably do something a bit more clean here...
            exit('It looks like you canceled your authorisation.'.\Html::anchor('users/oath/ Click here').' to try again.');
        }

        // set template vars
        $this->template->title = 'Index';
        $this->template->content = $view;

    }

}
