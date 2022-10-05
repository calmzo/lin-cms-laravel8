<?php

namespace App\Traits;

use App\Validators\HelpValidator;

trait HelpTrait
{

    public function checkHelp($id)
    {
        $validator = new HelpValidator();

        return $validator->checkHelp($id);
    }

    public function checkHelpCache($id)
    {
        $validator = new HelpValidator();

        return $validator->checkHelpCache($id);
    }

}
