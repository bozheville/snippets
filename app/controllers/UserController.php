<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 3/23/15
 * Time: 6:59 PM
 */

class UserController extends ControllerBase {

    public function authAction(){
        $request = new Phalcon\Http\Request();
        $security = new Phalcon\Security();
        $response = new Phalcon\Http\Response();
        $di = Phalcon\DI::getDefault();
        if($request->isGet() === true) {
            if($di->getSession()->has('session_id')){
                return $response->redirect('user/profile');
            }
            if($this->session->has('back_url')){
                $this->view->setVar('back_url', $this->session->get('back_url'));
                $this->session->remove('back_url');
            }
        } elseif($request->isPost() === true) {
            $user = User::findFirst([['email' => $request->getPost('email')]]);
            if(!$user){
                $user = new User();
                $user->email = $request->getPost('email');
                $user->password = $security->hash($request->getPost('password'));
                $user->save();
                $di->getSession()->set('session_id', $user->_id);
                $this->session->set("user", $user->_id);
                $back_url = $this->request->getPost('back_url') ? : 'user/profile';
                return $response->redirect($back_url);
            } elseif($security->checkHash($request->getPost('password'), $user->password)){
                $di->getSession()->set('session_id', $user->_id->{'$id'});
                $back_url = $this->request->getPost('back_url') ? : '';
                return $response->redirect($back_url);
            } else{
                return $response->redirect('user/auth#err');
            }

        }
    }

    public function profileAction(){
        $user  = User::getLogged($this->session->get('session_id'));
        if($this->request->isGet() === true){

        } elseif($this->request->isPost() === true){
            $user->name = $this->request->getPost('name');
            $user->save();
        }
        $this->view->setVar('user', $user);
    }

    public function logoutAction(){
        $this->session->destroy();
        return $this->response->redirect();
    }


}