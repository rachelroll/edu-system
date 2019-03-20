<?php

namespace App\Http\View\Composers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
//use App\Repositories\UserRepository;

class LayoutComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    //protected $user;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        //$this->user = $user;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();
        // 首先判断用户是否登录
        if ($user) {
            $user_id = Auth::user()->id;
            $user = User::where('id', $user_id)->withCount('notifications')->first();
            $notifications_count = $user->notifications_count;
            $view->with('notifications_count', $notifications_count);
        } else {
            $view->with('notifications_count', null);
        }

    }
}