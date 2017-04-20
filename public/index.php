<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__ . '/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
//
//$dbname = "asdasdasdasdds";
//$link = mysqli_connect("localhost", "root", "");
//$sql = "CREATE DATABASE $dbname";
//mysqli_query($link, $sql);
//mysqli_select_db($link, $dbname);
//$query = file_get_contents("vesyl.sql");
//if (mysqli_multi_query($link, $query)) {
//    mysqli_close($link);
//} else {
//    mysqli_close($link);
//}
//
//$link = mysqli_connect("localhost", "root", "", $dbname);
//
//if ($link === false) {
//    die("ERROR: Could not connect. " . mysqli_connect_error());
//}
//$sql = "INSERT INTO shops (shop_name,user_id) VALUES ('Johnasdasd',1)";
//if (mysqli_query($link, $sql)) {
//
//    echo "Records inserted successfully.";
//} else {
//    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
//
//}
//mysqli_close($link);
//die;
//
