<?php

namespace app\controllers;

use app\classes\Csrf;
use app\classes\Redirect;
use app\classes\View;
use app\controllers\auth\SessionController as session;
use app\models\posts;

class UserpostsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $ua = session::sessionValidate();
        if (is_null($ua)) {
            View::render('home', ['ua' => ['sv' => 0], 'title' => 'Foro Fie']);
            exit();
        }
        View::render('myposts', ['ua' => $ua, 'title' => 'Mis Publicaciones']);
    }


    public function getMyPosts($params = null)
    {
        $posts = new posts;
        $res = $posts->getUserPosts($params);
        echo $res;
    }

    public function newPost(){

        $Csrf = new Csrf;
        $ua = session::sessionValidate() ?? ['sv' => 0];
        View::render('newposts', ['ua' => $ua, 'csrf' => $Csrf->get_token()]);
    }

    public function saveNewPost(){
        $post = new posts;
        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        if(!isset($data['csrf_token']) || !Csrf::validate($data['csrf_token'])){
            $session = new session();
            $session->logout();
            return;
        }
        if(!isset($data['title']) || !isset($data['body'])){
            echo json_encode(['r' => false, 'code' => 1]); /* 1 = datos incompletos */
        }

        $data['userId'] = session::sessionValidate()['id'];
        $post->saveNewPost($data);
        Redirect::to('home');
    }

}
