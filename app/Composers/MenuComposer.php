<?php

namespace App\Composers;

use Illuminate\View\View;

class MenuComposer
{
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
                    'icon' => 'heroicon-m-envelope',
                    'label' => 'Emails',
                    'url' => route('emails.index'),
                    'active' => request()->routeIs('emails.*'),
                ],
                [
                    'icon' => 'heroicon-m-cpu-chip',
                    'label' => 'Chatbot',
                    'url' => route('chatbot.index'),
                    'active' => request()->routeIs('chatbot.*'),
                ],
            ]
        );
    }
}
