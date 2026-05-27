<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function health(): string
    {
        return 'Evenira EMS OK';
    }
}
