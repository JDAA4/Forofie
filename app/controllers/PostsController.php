<?php

namespace app\controllers;

use app\models\posts;

class PostsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPosts()
    {
        $posts = new posts();
        $result = $posts->getAllPosts();
        echo $result;
    }
    public function getLastPost(){
        $posts = new posts();
        $result = $posts->lastPost();
        echo $result;
    }
}
