<?php

class Config
{

    public static function setSiteName()
    {
        if (strpos($_POST["sitename"], '.myshopify.com') !== false) {
            $siteName = $_POST["sitename"];
            $tempSiteName = strval($siteName);
            $_SESSION['name'] = $tempSiteName;
            return $_SESSION['name'];
            echo "<script type= 'text/javascript'>alert('Invalid URL!');</script>";
            self::deleteCookies();
            header("Refresh:0; url=index.php?_ijt=o1gqfk2t3tkbr1ltv51bus7lcb");
            die();
        }
        return "Site name is not specified!";
    }
}