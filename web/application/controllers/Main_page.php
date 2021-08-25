<?php

use Model\Boosterpack_model;
use Model\Comment_model;
use Model\Post_model;
use Model\User_model;

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();

        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts = Post_model::preparation_many(Post_model::get_all(), 'default');

        return $this->response_success(['posts' => $posts]);
    }

    public function get_boosterpacks()
    {
        $posts = Boosterpack_model::preparation_many(Boosterpack_model::get_all(), 'default');

        return $this->response_success(['boosterpacks' => $posts]);
    }

    public function get_post(int $post_id)
    {
        $post = Post_model::preparation(Post_model::get_post($post_id), 'full_info');
        if ($post === NULL)
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        return $this->response_success(['post' => $post]);
    }

    public function comment()
    {
        if ( ! User_model::is_logged()) {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $comment = Comment_model::add_comment($this->input->post());

        return $this->response_success(['comment' => $comment]);
    }

    public function login()
    {
        $user = User_model::find_user_by_email($this->input->post_get('login'));
        if ($user->get_id()) {
            if ($user->validate_password($this->input->post_get('password')))
            {
                $this->session->set_userdata('id', $user->get_id());
                return $this->response_success(['user' => $user]);
            }
        }

        return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_WRONG_PARAMS);
    }

    public function logout()
    {
        $this->session->unset_userdata('id');

        redirect('/');
    }

    public function add_money()
    {
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $sum = (float)App::get_ci()->input->post('sum');

        $user = User_model::get_user();

        if ( $user->add_money($sum) )
        {
            return $this->response_success();
        } else {
            return $this->response_error(System\Libraries\Core::DB_STATE_ERROR);
        }
    }

    public function buy_boosterpack()
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        //TODO логика покупки и открытия бустерпака по алгоритмку профитбанк, как описано в ТЗ
    }


    /**
     *
     * @return object|string|void
     */
    public function like_comment(int $comment_id)
    {
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $user = User_model::get_user();
        $comment = Comment_model::get_comment($comment_id);
        if ( $comment->increment_likes($user) )
        {
            return $this->response_success(
                [
                    'user' => User_model::preparation($user),
                    'comment' => Comment_model::preparation($comment),
                ]);
        } else {
            return $this->response_error(User_model::RESPONSE_NO_ENOUGH_LIKES);
        }

    }

    /**
     * @param int $post_id
     *
     * @return object|string|void
     */
    public function like_post(int $post_id)
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $post = Post_model::get_post($post_id);
        $user = User_model::get_user();

        if ( $post->increment_likes($user))
        {
            return $this->response_success(
                [
                    'user' => User_model::preparation($user),
                    'post' => Post_model::preparation($post),
                ]);
        } else {
            return $this->response_error(User_model::RESPONSE_NO_ENOUGH_LIKES);
        }
    }


    /**
     * @return object|string|void
     */
    public function get_boosterpack_info(int $bootserpack_info)
    {
        // Check user is authorize
        if ( ! User_model::is_logged())
        {
            return $this->response_error(System\Libraries\Core::RESPONSE_GENERIC_NEED_AUTH);
        }


        //TODO получить содержимое бустерпак
    }
}
