<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {}

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('*', function ($view) {
            $menu = $this->buildMenu();
            $view->with('menu', $menu);
        });
    }

    private function buildMenu($menuId = null, $level = 'second')
    {
        $returnMenu = '';
        $menus      = session()->get('menu');

        if (is_null($menus)) {
            return $returnMenu;
        }

        foreach ($menus as $menu) {
            if (is_null($menuId)) {
                $url       = route($menu->route);
                $classCss  = isActiveRoute($menu->route);
                $iconLevel = '';
                $subMenu   = '';
                
                foreach ($menus as $m) 
                {
                    if($menu->id === intval($m->parent_id))
                    {
                        $subMenu .= '<ul class="submenu">
                            <li class="' . isActiveRoute($m->route) . '">
                                <a class="submenu-option" href="' . route($m->route) . '"><i class="fa ' . $m->icon . '"></i> <span class="nav-label">&nbsp;&nbsp;' . $m->name . '</span>' . $iconLevel . '</a>
                            </li></ul>';
                    } 
                }   
                
                if(null === $menu->parent_id)
                {
                    $returnMenu .= '
                    <li class="' . $classCss . '">
                        <a href="' . $url . '"><i class="fa ' . $menu->icon . '"></i> <span class="nav-label">' . $menu->name . '</span>' . $iconLevel . '</a>
                        ' . $subMenu . '
                    </li>';
                }                
            }
        }

        return $returnMenu;
    }
}
