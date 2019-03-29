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
}
