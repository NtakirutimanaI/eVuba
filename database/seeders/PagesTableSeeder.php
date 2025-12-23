<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Page;


class PagesTableSeeder extends Seeder
{
public function run()
{
$pages = [
['name' => 'dashboard','label' => 'Dashboard'],
['name' => 'users','label' => 'Users'],
['name' => 'employees','label' => 'Employees'],
['name' => 'customers','label' => 'Customers'],
['name' => 'services','label' => 'Services'],
['name' => 'tasks','label' => 'Tasks'],
['name' => 'inventory','label' => 'Inventory'],
['name' => 'schedules','label' => 'Schedules'],
['name' => 'reports','label' => 'Reports'],
['name' => 'settings','label' => 'Settings'],
];


foreach($pages as $p) Page::firstOrCreate(['name' => $p['name']], ['label' => $p['label']]);
}
}