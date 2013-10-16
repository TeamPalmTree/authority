<?php

namespace Fuel\Tasks;

class Setup
{

    public static function run()
    {
        // call install
        Install::run();

        $writable_paths = array(APPPATH.'cache', APPPATH.'logs', APPPATH.'tmp', APPPATH.'config');

        foreach ($writable_paths as $path)
        {
            if (@chmod($path, 0777))
            {
                \Cli::write("\t".'Made writable: '.$path, 'green');
            }

            else
            {
                \Cli::write("\t".'Failed to make writable: '.$path, 'red');
            }
        }
    }
}
