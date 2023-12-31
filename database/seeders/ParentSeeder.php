<?php

namespace Database\Seeders;

use App\Models\ParentMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParentMenu::create([
            'parent_menu' => 'Admin',
            'icon' => 'hash',
        ]);
        ParentMenu::create([
            'parent_menu' => 'Kurikulum',
            'icon' => 'book-open',
        ]);
        ParentMenu::create([
            'parent_menu' => 'Sarpras',
            'icon' => 'layers',
        ]);
        ParentMenu::create([
            'parent_menu' => 'Manajemen',
            'icon' => 'target',
        ]);
        ParentMenu::create([
            'parent_menu' => 'Perpustakaan',
            'icon' => 'book',
        ]);
        ParentMenu::create([
            'parent_menu' => 'BankMini',
            'icon' => 'dollar-sign',
        ]);
        ParentMenu::create([
            'parent_menu' => 'Sitepat',
            'icon' => 'eye',
        ]);
        ParentMenu::create([
            'parent_menu' => 'PPDB',
            'icon' => 'users',
        ]);
    }
}
