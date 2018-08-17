<?php

namespace ahvla\controllers;

class PagesController extends BaseController
{

    public function getHelp()
    {
        return $this->makeView('pages.help', []);
    }
}