<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Role;


class RolesTableSeeder extends Seeder
{
public function run()
{
$roles = ['admin','manager','employee','customer'];
foreach($roles as $r) Role::firstOrCreate(['name' => $r]);
}
}