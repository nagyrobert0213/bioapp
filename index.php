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
    <div class="col col-lg-3 md-4">
        <form method="post" action="">
            <label>Reset page</label>
            <input type="submit" class="form-control" name="reset" id="reset" value="Reset"
                   required="required"/><br/><br/>
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

    Config::redirectPage();

    Config::setSiteName();

    Database::setupDatabase(Database::getData());

    $conn = Database::getData();
    $code = Config::getCode();
    $accessToken = Config::getAccessToken($code);
    $shopify = Config::configShopify($accessToken);

    echo "<br> Your code is : ";
    print_r($code);

    echo "<br> Your access token is : ";
    print_r($accessToken);

    echo "<br>Using webshop : ";
    print_r($_SESSION['name']);


    if (isset($_POST["access"])) {
        try {
            Database::addToDatabase($_SESSION['name'], $conn);
        } catch (Exception $exception) {
            echo $conn->error;
        }
    }

    if (isset($_POST["access"])) {
        SendApi::createCustomer($shopify);
        SendApi::createWebhook($shopify);
        SendApi::getCustomers($shopify);
    }

    if (isset($_POST["reset"])) {
        Config::deleteCookies();
    }

    if (isset($_POST["drop"])) {
        Database::dropTable($conn);
    }
    ?>
</div>
</body>
</html>
