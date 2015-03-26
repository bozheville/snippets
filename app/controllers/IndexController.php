<?php

class IndexController extends ControllerBase
{

    public function indexAction() {
        $di = Phalcon\DI::getDefault();
        $this->view->setVar('User', User::getLogged($di->getSession()->get("session_id")));
        $this->view->setVar('snippets', Snippet::getPage());
        $this->view->setVar('pagination', Snippet::getPagination());
        $this->view->pick("snippets/index");
    }

}

