<?php

namespace App\Http\Controllers\Admin;

class HomeStepController extends SimpleItemController
{
    protected string $table = 'home_steps';
    protected string $slug = 'manage-home-steps';
    protected string $pageTitle = 'Apply Steps';
    protected string $pageSubtitle = 'Steps in the homepage How to Apply section';
    protected string $addLabel = 'Add Step';
}
