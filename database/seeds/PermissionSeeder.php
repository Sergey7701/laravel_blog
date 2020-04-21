<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manageUser           = new Permission();
        $manageUser->name     = 'Manage users';
        $manageUser->slug     = 'manage-users';
        $manageUser->save();
        $createArticles       = new Permission();
        $createArticles->name = 'Manage Articles';
        $createArticles->slug = 'manage-articles';
        $createArticles->save();
        
    }
}
