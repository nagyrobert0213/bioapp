<?php

class SendApi
{

    public static function getCustomers($shopify)
    {
        if (empty($_SESSION['name'])) {
            return null;
        } else {
            $limit = 10;
            try {
                $customerCount = $shopify->Customer->count();
            } catch (Exception $exception) {
                Config::deleteCookies();
            }
            $pageCount = $customerCount / $limit;
            $pageCount = ceil($pageCount);
            echo 'Customer count : ' . $customerCount . '<br>';
            echo 'Page count : ' . $pageCount . '<br>';
            echo 'Limit per request : ' . $limit . '<br>';

            for ($i = 1; $i <= $pageCount; $i++) {
                ${'params' . $i} = array(
                    'limit' => $limit,
                    'fields' => 'first_name,last_name,email',
                    'page' => $i
                );
            }

            echo '<br>';
            echo 'Customers: ' . '<br>';
            foreach (range(1, $pageCount) as $i) {
                ${'customers' . $i} = $shopify->Customer->get(${'params' . $i});
            }
            $y = $customerCount;

            echo
            '
       <div>
        <table class="table">
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>E-mail</th>
              <th>Number</th>
            </tr>
          </thead>
          <tbody>
          ';

            for ($i = 0; $i <= $pageCount; $i++) {
                foreach ((array)${'customers' . $i} as $customer):
                    echo '<tr>';
                    print_r('<td>' . $customer['first_name']);
                    print_r(" ");
                    print_r('<td>' . $customer['last_name']);
                    print_r(" ");
                    print_r('<td>' . $customer['email']);
                    ${'array' . $y} = array($customer['first_name'] . ' ', $customer['last_name'] . ' ', $customer['email']);
                    print_r('<td>' . $y);
                    $y -= 1;
                    echo '</tr>';
                endforeach;
            }
            echo
            '</tbody>
          </table>
         </div>';
        }
    }

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