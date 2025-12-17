<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class LinkTransactionItemsToProductsSeeder extends Seeder
{
    public function run(): void
    {
        $items = DB::table('transaction_items')
            ->whereNull('product_id')
            ->orWhere('product_id', 0)
            ->get();

        echo "Found {$items->count()} transaction items to link\n";

        foreach ($items as $item) {
            // Try to find product by name or SKU
            $product = Product::where('name', 'LIKE', '%' . $item->product_name . '%')
                ->orWhere('sku', $item->sku)
                ->first();

            if ($product) {
                DB::table('transaction_items')
                    ->where('id', $item->id)
                    ->update(['product_id' => $product->id]);
                echo "Linked '{$item->product_name}' to product #{$product->id}\n";
            } else {
                echo "No matching product found for '{$item->product_name}'\n";
            }
        }

        echo "Done!\n";
    }
}
