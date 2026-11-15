<?php

namespace App\Http\Controllers\Admin;

class HomeAccreditationController extends SimpleItemController
{
    protected string $table = 'home_accreditations';
    protected string $slug = 'manage-home-accreditations';
    protected string $pageTitle = 'Accreditations';
    protected string $pageSubtitle = 'Items in the homepage accreditation strip';
    protected string $addLabel = 'Add Item';
}
