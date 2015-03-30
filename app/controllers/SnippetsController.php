<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 3/23/15
 * Time: 12:43 PM
 */

class SnippetsController extends ControllerBase {

    public function indexAction($page = 1){
        $this->view->setVar('snippets', Snippet::getPage($page));
        $this->view->setVar('pagination', Snippet::getPagination($page));
    }

    public function addAction(){
//        $request  = new Phalcon\Http\Request();
//        $response = new Phalcon\Http\Response();
        $user = User::getLogged($this->session->get('session_id'));
        if($user->_id){
            if($this->request->isGet() === true){

            } elseif($this->request->isPost() === true){
//            print_r($request->getPost());die;
                $snippet = new Snippet();
                $snippet->title = $this->request->getPost('title');
                $snippet->body = $this->request->getPost('body');
                $snippet->demo = $this->request->getPost('demo');
                $snippet->tags = trim($this->request->getPost('tags'));
                $snippet->save();
                return $this->response->redirect('snippets/' . $snippet->num);
            }
        } else{
            $this->session->set('back_url', 'add');
            return $this->response->redirect('user/auth');
        }

    }

    public function viewAction($id){
        $snippet = Snippet::findFirst([['num' => (int) $id]]);
        if($snippet->created['user']){
            $author = User::findFirst(['_id' => new MongoId($snippet->created['user'])]);
            if($author){
                $author = $author->toArray();
                unset($author['password']);
                unset($author['created']);
                unset($author['email']);
                $author['_id'] = $author['_id']->{'$id'};
                $snippet->created['user'] = $author;
            }
        }

        $this->view->setVar('snippet', $snippet);


    }

    public function editAction($id){
        $request  = new Phalcon\Http\Request();
        $snippet = Snippet::findFirst([['num' => (int) $id]]);
        if($request->isGet() === true){
            $this->view->setVar('snippet', $snippet);
        } elseif($request->isPost() === true){
            $snippet->title = $request->getPost('title');
            $snippet->body = $request->getPost('body');
            $snippet->demo = $this->request->getPost('demo');
            $snippet->tags = trim($request->getPost('tags'));
            $snippet->save();
            return $this->response->redirect('snippets/' . $snippet->num);
        }
    }


}