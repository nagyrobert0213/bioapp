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
            }
        return "Site name is not specified!";
    }
}
