<html lang="">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<br>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">Tutorial</div>
        <div class="panel-body">If you would like to install the application, please provide the webshop URL in the
            given place.
            If you have already installed the application and the 'Using webshop:' is blank, please type in the URL
            again.
            If you can see both code and webshop, then click 'Get access token and send APIs'.
        </div>
    </div>
</div>

<div class="form-group container">
    <div class="col col-lg-6 md-4">
        <form method="post" action="">
            <label>Website Name :</label>
            <input type="text" class="form-control" name="sitename" id="sitename" required="required"
                   placeholder="example-store.myshopify.com"/>
            <input type="submit" class="form-control" value="Submit"/><br/><br/>
        </form>
    </div>
    <div class="col col-lg-6 md-4">
        <form method="post" action="">
            <label>Get access token and send APIs</label>
            <input type="submit" class="form-control" name="access" id="access" required="required"/><br/><br/>
        </form>
    </div>
</div>
<div class="form-group col col-lg-6 md-4">

    <?php
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/config.php';
    require __DIR__ . '/sendapi.php';
    require __DIR__ . '/database.php';

    session_set_cookie_params(300, "/");
    session_start();



    Config::setSiteName();

    parse_str($_SERVER['QUERY_STRING'], $output);
    print_r('Your code is : ');
    print_r($output['code']);
    $code = ($output['code']);

    if (isset($_POST["access"])) {
        $data = array(
            'client_id' => 'd5be83c3359ee036972a3397bd61553a',
            'client_secret' => '31ac2ad348f3ff9cdaf3aaf09ea3688b',
            'code' => $code
        );
        $data_string = json_encode($data);

        $ch = curl_init('https://' . $_SESSION['name'] . '/admin/oauth/access_token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $output = json_decode($result, true);
        print_r("<br>");
        $accessToken = $output["access_token"];
    }
    // //
    echo "<br> Your access token is : ";
    print_r($accessToken);
    echo "<br>Using webshop : ";
    print_r($_SESSION['name']);

    $config = array(
        'ShopUrl' => $_SESSION['name'],
        'ApiKey' => 'd5be83c3359ee036972a3397bd61553a',
        'Password' => 'b614fc2db48e2cc63e78bdce44339f98',
        'SharedSecret' => 'f2bccc1bfbf73cf540f25999ea7093926a4b6c4bf95d761d1ef084086057a103',
        'AccessToken' => $accessToken,
    );

    PHPShopify\ShopifySDK::config($config);


    $scopes = 'read_products,read_orders,write_script_tags,read_customers,write_customers';
    $redirectUrl = 'https://shopifybio.herokuapp.com/index.php?_ijt=o1gqfk2t3tkbr1ltv51bus7lcb';
    
    try {
        \PHPShopify\AuthHelper::createAuthRequest($scopes, $redirectUrl, null, null, true);
    } catch (\PHPShopify\Exception\SdkException $e) {
    }

    PHPShopify\ShopifySDK::config($config);

    try {
        $access = \PHPShopify\AuthHelper::createAuthRequest($scopes);
    } catch (\PHPShopify\Exception\SdkException $e) {
    }
    $shopify = new PHPShopify\ShopifySDK($config);

    $params = array(
        'fields' => 'first_name,last_name,email'
    );
    echo '<br>';
    echo 'Customers: ' . '<br>';
    $customers = $shopify->Customer->get($params);
    foreach ($customers as $customer):
        print_r('<br>');
        print_r($customer['first_name']);
        print_r(" ");
        print_r($customer['last_name']);
        print_r(" ");
        print_r($customer['email']);
    endforeach;

    $newCustomer = array(
        "email" => "nagyrobert0213@gmail.com",
        "first_name" => "Robert",
        "last_name" => "Nagy"
    );
    $shopify->Customer->post($newCustomer);

    $newWebhook = array(
        "topic" => "customers/create",
        "address" => "https://shopifybio.herokuapp.com/",
        "format" => "json"
    );
    $shopify->Webhook->post($newWebhook);
    ?>
</div>
</body>
</html>
