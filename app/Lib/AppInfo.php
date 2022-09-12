<?php

namespace App\Lib;

class AppInfo
{

    protected $name = 'Calm';

    protected $alias = 'CTC';

    protected $link = 'https://calmchen.com';

    protected $version = '1.0.0';

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
        return null;
    }

}
