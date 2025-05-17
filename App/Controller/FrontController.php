<?php

if(file_exists("app/config/component/ConfigSystem.php")){
    require_once("app/config/component/ConfigSystem.php");
}

if (isset($_REQUEST['url'])) {
    if (file_exists("app/Controller/{$_REQUEST['url']}Controller.php")) {
        require_once("app/Controller/{$_REQUEST['url']}Controller.php");
    } else {
        header("Location: ?url=login");
    }
} else {
    header("Location: ?url=login");
}

?>