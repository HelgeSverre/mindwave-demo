<?php

namespace App\Composers;

use App\Services\Bob;
use Illuminate\View\View;

class MenuComposer
{
    protected Bob $bob;

    public function __construct(Bob $bob)
    {

        $this->bob = $bob;
    }

    public function compose(View $view)
    {
        $view->with(
            key: 'menuLinks',
            value: [
                [
                    'icon' => 'heroicon-m-square-2-stack',
                    'label' => 'Dashboard',
                    'url' => route('dashboard'),
                    'active' => request()->routeIs('dashboard'),
                ],
                [
                    'icon' => 'heroicon-m-document-text',
                    'label' => 'Documents',
                    'url' => route('documents.index'),
                    'active' => request()->routeIs('documents.*'),
                ],

                [
                    'icon' => 'heroicon-m-cpu-chip',
                    'label' => 'Chatbot',
                    'url' => route('robot.index'),
                    'active' => request()->routeIs('robot.*'),
                ],
            ]
        );
    }
}
