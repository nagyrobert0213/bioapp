<?php

class SendApi
{

    public static function createCustomer($shopify)
    {
        if (empty($_SESSION['name'])) {
            return null;
        } else {
            try {
                for ($i = 0; $i <= 30; $i++) {
                    $newCustomer = array(
                        "email" => "nagyrobert$i@gmail.com",
                        "first_name" => "Roberts",
                        "last_name" => "Nagey"
                    );
                    $shopify->Customer->post($newCustomer);
                }
            } catch (Exception $exception) {
                echo '<br><br>Customer already created.';
            }
        }
    }

    public static function createWebhook($shopify)
    {
        if (empty($_SESSION['name'])) {
            return null;
        } else {
            try {
                $newWebhook = array(
                    "topic" => "customers/create",
                    "address" => "https://shopifybio.herokuapp.com/",
                    "format" => "json"
                );
                $shopify->Webhook->post($newWebhook);
            } catch (Exception $exception) {
                echo '<br>Webhook already created.<br>';
            }
        }
    }
}