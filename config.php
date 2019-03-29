<?php

class Config
{

    public static function setSiteName()
    {
        if (!empty($_POST["sitename"])) {
            if (strpos($_POST["sitename"], '.myshopify.com') !== false) {
                $siteName = $_POST["sitename"];
                $tempSiteName = strval($siteName);
                $_SESSION['name'] = $tempSiteName;
                return $_SESSION['name'];
            } else {
                echo "<script type= 'text/javascript'>alert('Invalid URL!');</script>";
                self::deleteCookies();
                header("Refresh:0; url=index.php?_ijt=o1gqfk2t3tkbr1ltv51bus7lcb");
                die();
            }
        }
        return "Site name is not specified!";
    }

    public static function getCode()
    {
        parse_str($_SERVER['QUERY_STRING'], $output);
        $code = ($output['code']);
        return $code;
    }

    public static function deleteCookies()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
            header("Refresh:0; url=index.php?_ijt=o1gqfk2t3tkbr1ltv51bus7lcb");
            die();
        }
    }

    public static function getAccessToken($code)
    {
        if (isset($_POST["access"])) {
            if (empty($_SESSION['name'])) {
                echo "<script type= 'text/javascript'>alert('Website is not specified!');</script>";
            } else {
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

                return $accessToken;
            }
        }
        return "";
    }

}
