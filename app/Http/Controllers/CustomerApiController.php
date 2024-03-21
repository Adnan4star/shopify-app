<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use Exception;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function getCustomers()
    {
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
            $result = $api->rest('GET', '/admin/api/2024-01/customers.json');
            // dd($result);

            $fetched_customers = $result['body']->container;

            foreach($fetched_customers['customers'] as $customerData) {
                
                $customer = new Customer();
                $customer->s_id = $customerData['id'];
                $customer->first_name = $customerData['first_name'];
                $customer->last_name = $customerData['last_name'];
                $customer->orders_count = $customerData['orders_count'];
                $customer->total_spent = $customerData['total_spent'];
                $customer->last_order_id = $customerData['last_order_id'];
                $customer->currency = $customerData['currency'];
                $customer->save();

                foreach($customerData['addresses'] as $customerAddress) {
                    // dd($customerData);
                    $address = new Address();
                    $address->customer_id = $customer->id;
                    $address->s_id = $customerAddress['id'];
                    $address->address1 = $customerAddress['address1'];
                    $address->address2 = $customerAddress['address2'];
                    $address->city = $customerAddress['city'];
                    $address->country = $customerAddress['country'];
                    $address->zip = $customerAddress['zip'];
                    $address->phone = $customerAddress['phone'];
                    $address->country_code = $customerAddress['country_code'];
                    $address->save();
                }
            
                return "Customers saved successfully";
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
