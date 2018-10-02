<?php

use App\Action;
use Illuminate\Database\Seeder;

class ActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Action::create(['name' => 'Acesso total al sistema', 'method' => '*', 'order' => 1]);

        Action::create(['name' => 'Lista de categorías', 'method' => 'categories.index', 'order' => 2]);
        Action::create(['name' => 'Registrar categoría', 'method' => 'categories.create', 'order' => 3]);
        Action::create(['name' => 'Actualizar categoría', 'method' => 'categories.update', 'order' => 4]);
        Action::create(['name' => 'Eliminar categoría', 'method' => 'categories.destroy', 'order' => 5]);

        Action::create(['name' => 'Lista de productos', 'method' => 'products.index', 'order' => 6]);
        Action::create(['name' => 'Registrar producto', 'method' => 'products.create', 'order' => 7]);
        Action::create(['name' => 'Detalle de producto', 'method' => 'products.show', 'order' => 8]);
        Action::create(['name' => 'Actualizar producto', 'method' => 'products.show|products.update', 'order' => 9]);
        Action::create(['name' => 'Eliminar producto', 'method' => 'products.destroy', 'order' => 10]);

        Action::create(['name' => 'Lista de precios', 'method' => 'prices.index', 'order' => 11]);
        Action::create(['name' => 'Registrar precios', 'method' => 'prices.create', 'order' => 12]);
        Action::create(['name' => 'Actualizar precios', 'method' => 'prices.show|prices.update', 'order' => 13]);
        Action::create(['name' => 'Eliminar precios', 'method' => 'prices.destroy', 'order' => 14]);

        Action::create(['name' => 'Lista de cotizaciones', 'method' => 'quotations.index', 'order' => 15]);
        Action::create(['name' => 'Registrar cotización', 'method' => 'quotations.create', 'order' => 16]);
        Action::create(['name' => 'Detalle de cotización', 'method' => 'quotations.show', 'order' => 17]);

        Action::create(['name' => 'Lista de perfiles', 'method' => 'profiles.index', 'order' => 18]);
        Action::create(['name' => 'Registrar perfiles', 'method' => 'profiles.create', 'order' => 19]);
        Action::create(['name' => 'Actualizar perfiles', 'method' => 'profiles.show|profiles.update', 'order' => 20]);
        Action::create(['name' => 'Eliminar perfiles', 'method' => 'profiles.destroy', 'order' => 21]);

        Action::create(['name' => 'Lista de usuarios', 'method' => 'users.index', 'order' => 22]);
        Action::create(['name' => 'Registrar usuario', 'method' => 'users.create', 'order' => 23]);
        Action::create(['name' => 'Detalle de usuario', 'method' => 'users.show', 'order' => 24]);
        Action::create(['name' => 'Actualizar usuario', 'method' => 'users.show|users.update', 'order' => 25]);
        Action::create(['name' => 'Eliminar usuario', 'method' => 'users.destroy', 'order' => 26]);

        Action::create(['name' => 'Lista de costos', 'method' => 'costs.index', 'order' => 27]);
        Action::create(['name' => 'Actualizar costos', 'method' => 'costs.update', 'order' => 28]);
    }
}
