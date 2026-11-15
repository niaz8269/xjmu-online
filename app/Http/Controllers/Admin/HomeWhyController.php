<?php

namespace App\Http\Controllers\Admin;

class HomeWhyController extends SimpleItemController
{
    protected string $table = 'home_why';
    protected string $slug = 'manage-home-why';
    protected string $pageTitle = 'Why-Us Cards';
    protected string $pageSubtitle = 'Cards in the homepage Why XJMU section';
    protected string $addLabel = 'Add Card';
}
