<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Permission;


class PermissionsTableSeeder extends Seeder
{
public function run()
{
$perms = ['view','create','edit','delete'];
foreach($perms as $p) Permission::firstOrCreate(['name' => $p]);
}
}