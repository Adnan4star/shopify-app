<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use Exception;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;

class ProductApiController extends Controller
{
    public function index()
    {
        return view('Apifetch');
    }

    public function getProducts()
    {
        try {
            session_start();
            
            $_SESSION['shop'] = 'staging-checkout-republic.myshopify.com'; 

            
            $options = new Options();
            $options->setType(true); // Makes it private
            $options->setVersion('2024-01');
            $options->setApiKey(env('SHOPIFY_API_KEY'));
            $options->setApiPassword(env('ADMIN_API_ACCESS_TOKEN'));

        
            if (!isset($_SESSION['shop'])) {
                throw new Exception("Shop session is not set.");
            }

            // Create the client and session
            $api = new BasicShopifyAPI($options);
            $api->setSession(new Session($_SESSION['shop']));

            // Make API call to retrieve products
            $result = $api->rest('GET', '/admin/api/2024-01/products.json');
            // dd($result);
            $fetched_products = $result['body']->container;
            
            foreach($fetched_products['products'] as $productData){
                $existingProduct = Product::where('handle', $productData['handle'])->first();
                
                if (!$existingProduct) {
                    // dd($productData);
                    $product = new Product();
                    $product->s_id = $productData['id'];
                    $product->title = $productData['title'];
                    $product->vendor = $productData['vendor'];
                    $product->product_type = $productData['product_type'];
                    $product->handle = $productData['handle'];
                    $product->save();
                    // dd($productData['variants']);
                    foreach($productData['variants'] as $variantData) {
                        // dd($variantData);
                        $variants = new Variant();
                        $variants->product_id = $product->id;
                        $variants->s_id = $variantData['id'];
                        $variants->s_product_id = $variantData['product_id'];
                        $variants->price = $variantData['price'];
                        $variants->sku = $variantData['sku'];
                        $variants->inventory_quantity = $variantData['inventory_quantity'];
                        $variants->save();
                    }

                    
                } else {
                    return "Products already exists";
                }
            }

            // Check if products were saved successfully
            if (count($fetched_products['products']) > 0) {
                echo "Products were successfully saved.";
            } else {
                echo "No products were fetched or saved.";
            }

            // dd($products);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}