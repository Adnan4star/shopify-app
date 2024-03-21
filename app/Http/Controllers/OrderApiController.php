<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function getOrders(){
        try{
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
            $result = $api->rest('GET', '/admin/api/2024-01/orders.json');
            // dd($result);

            $fetched_orders = $result['body']->container;

            foreach($fetched_orders['orders'] as $orderData) {
                $order = new Order();
                $order->s_order_id = $orderData['id'];
                $order->app_id = $orderData['app_id'];
                $order->admin_graphql_api_id = $orderData['admin_graphql_api_id'];
                $order->confirmation_number = $orderData['confirmation_number'];
                $order->current_subtotal_price = $orderData['current_subtotal_price'];
                $order->contact_email = $orderData['contact_email'];
                $order->order_number = $orderData['order_number'];
                $order->currency = $orderData['currency'];
                $order->save();
            }

            return "Orders saved successfully";

        }catch (Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }
}
