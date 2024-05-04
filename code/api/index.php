<?php
ob_start();
include("../lib/openCon.php");

 // Allow from any origin
 if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

//$valid_passwords = array ("crownfunding" => "Admin123");
//$valid_users = array_keys($valid_passwords);

//$user = $_SERVER['PHP_AUTH_USER'];
//$pass = $_SERVER['PHP_AUTH_PW'];

//$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
$validated = 1;

if (!$validated) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    $res = array("status"=>0, "message"=>"Unauthorized");
    print(json_encode($res));
    //die ("Not authorized");
    die();
}
else{
    $dirPath = "../";
    include("../lib/functions.php");
    require_once('../core/functions.php');
    $GLOBALS['fc'] = new Functions();

    require_once('../core/main.php');
    $main = new Main($GLOBALS['fc']);

    include('views.php');
    include('actions.php');
    global $baseURL;
    global $rootURL;

    $view = new JsonView();
    $action = $_REQUEST['action'];
    $library = new Actions($GLOBALS['fc'], $main);

    $json = file_get_contents('php://input');

    if(!empty($_POST)){
        if(isset($_REQUEST['UserID'])){
            $_POST['UserID'] = $_REQUEST['UserID'];
        }
        $data = $library->$action($_POST);
    }
    elseif (!empty($json)){
        $arr = objectToArray(json_decode($json, false));
        if(isset($_REQUEST['UserID'])){
            $arr['UserID'] = $_REQUEST['UserID'];
        }
        $data = $library->$action($arr);
    }
    else {
        $data = $library->$action($_REQUEST);
    }
    //$view->render($data);
    gzencode($view->render($data), 9);
}

ob_end_flush();