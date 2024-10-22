<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class MarketProductsController extends Controller
{
    public function index()
    {
        // Hardcoded products data with relevant images from cdn.mafrservices.com
        $products = [
            ['name' => 'Nescafe Coffee', 'price' => 157.00, 'image' => 'https://images.pexels.com/photos/7507582/pexels-photo-7507582.jpeg'], // Coffee cup
            ['name' => 'Cadbury Cocoa', 'price' => 172.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h1a/hce/9428046315550/25993_2.jpg'],
            ['name' => 'Chocolate Biscuit', 'price' => 156.00, 'image' => 'https://images.pexels.com/photos/3252137/pexels-photo-3252137.jpeg'], // Chocolate milk
            ['name' => 'Raha Cocoa', 'price' => 52.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/hb8/hc1/12456719646750/17254_Main.jpg'], // Hot cocoa
            ['name' => 'Almond Milk', 'price' => 95.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/heb/h30/49295522693150/197575_Main.jpg'], // Almond milk
            ['name' => 'Tropicana Orange Juice', 'price' => 210.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h95/h16/11202277867550/342730_main.jpg'], // Orange juice
            ['name' => 'Whole Milk', 'price' => 89.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h80/h80/16930277851166/43275_main.jpg'], // Whole milk in a glass
            ['name' => 'Starbucks Espresso', 'price' => 250.00, 'image' => 'https://cdn.mafrservices.com/pim-content/KEN/media/product/230040/1721477403/230040_main.jpg'], // New Espresso image
            ['name' => 'Oat Milk', 'price' => 115.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h25/h87/26834098618398/157774_main.jpg'], // Oat milk
            ['name' => 'Dairyland Ice Cream', 'price' => 190.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h8b/had/12441293029406/6536_Main.jpg'], // Ice cream scoops
            ['name' => 'Cappuccino Coffee', 'price' => 175.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h00/hca/17384193490974/107561_main.jpg'], // Cappuccino
            ['name' => 'Chocolate Syrup', 'price' => 120.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h5e/haa/26675969359902/138234_main.jpg'], // Chocolate syrup bottle
            ['name' => 'Strawberry Milk', 'price' => 105.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/he6/he5/11671575003166/491024_main.jpg'], // Strawberry milkshake
            ['name' => 'Vanilla Yogurt', 'price' => 140.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h8a/hc8/16871990427678/156407_main.jpg'], // Vanilla Yogurt
            ['name' => 'Irish Cream Coffee', 'price' => 135.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h4b/h2b/12456326561822/33577_Main.jpg'], // Irish coffee with cream
            ['name' => 'Kenyan Arabica Coffee', 'price' => 220.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h3d/h3b/28761956024350/144607_main.jpg'], // Arabica coffee
            ['name' => 'Hazelnut Coffee', 'price' => 165.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h3d/h3b/28761956024350/144607_main.jpg'], // Hazelnut coffee
            ['name' => 'Milo Drink', 'price' => 185.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/hf9/h29/12456931328030/71271_Main.jpg'], // Milo drink
            ['name' => 'Cadbury Drinking Chocolate', 'price' => 145.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h69/hf7/28268450152478/31708_main.jpg'], // Drinking chocolate
            ['name' => 'Skimmed Milk', 'price' => 75.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h29/hde/33434016579614/81480_main.jpg'], // Skimmed milk
            // ['name' => 'Double Chocolate Frappe', 'price' => 190.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h01/haf/16199238115358/292047_Main.jpg'], // Double chocolate frappe
            // ['name' => 'Coffee Mocha', 'price' => 155.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h9e/h37/18029383715294/13202_Main.jpg'], // Mocha coffee
            // ['name' => 'Coconut Milk', 'price' => 130.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h41/h2c/9428037084446/13492_Main.jpg'], // Coconut milk
            // ['name' => 'Kenyan Robusta Coffee', 'price' => 195.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h6f/h80/15002785161278/21353_Main.jpg'], // Coffee beans (robusta)
            // ['name' => 'Soy Milk', 'price' => 105.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h85/h58/12009350412318/144937_Main.jpg'], // Soy milk
            // ['name' => 'Hazelnut Latte', 'price' => 145.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/hc9/ha2/12487356719198/274092_Main.jpg'], // Hazelnut latte
            // ['name' => 'Caramel Macchiato', 'price' => 180.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h29/hf0/15502490251262/299032_Main.jpg'], // Caramel macchiato
            // ['name' => 'Peppermint Hot Chocolate', 'price' => 200.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h26/hd2/15502500892606/322032_Main.jpg'], // Peppermint hot chocolate
            // ['name' => 'Chai Latte', 'price' => 160.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h36/h54/17008345964542/14348_Main.jpg'] // Chai latte
        ];

        // Paginate the products (8 per page)
        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($products, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new LengthAwarePaginator($currentItems, count($products), $perPage);
        $paginatedProducts->setPath(route('miller-admin.marketplace-products'));

        // Pass the paginated products to the view
        return view('pages.miller-admin.market-auction.products', compact('paginatedProducts'));
    }
}
