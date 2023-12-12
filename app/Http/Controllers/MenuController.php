<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends BaseApiController
{

    public function __construct()
    {
    }

    public function index()
    {
        $menus = Menu::filter()->with('items')->get();

        $menus->each(function ($menu) {
            $menu->items->each(function ($item) {
                $this->loadSubsRecursively($item);
                $childrenImages = $item->childrenImages();
                $childrenImages ? ($item->childrenImages = $childrenImages) : null;
            });
        });

        return $menus;
    }

    public function show($id)
    {
        $menu = Menu::find($id);

        $menu->items->each(function ($item) {
            $this->loadSubsRecursively($item);
            $childrenImages = $item->childrenImages();
            $childrenImages ? ($item->childrenImages = $childrenImages) : null;
        });

        return $menu;
    }

    function loadSubsRecursively($item) {
        $subs = $item->subs()->get();

        if ($subs->count() > 0) {
            $subs->each(function ($sub) {
                $this->loadSubsRecursively($sub);
            });
        }

        $item->subs = $subs;
    }
}
