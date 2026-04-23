<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MessageBox extends Component
{
    public $title; // ここでプロバティを定義

    public function __construct($title = null)
    {
        $this->title = $title; // ここで受け取る
    }

    public function render(): View|Closure|string
    {
        return view('components.message-box');
    }
}
