<?php

namespace App\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        if (auth("webadmin")->check()) {
            return auth("webadmin")->user()->id."backfiles";
        } else if (auth("web")->check()) {
            return auth("web")->user()->id."usersfiles";
        }
        // return parent::userField();
    }
}
