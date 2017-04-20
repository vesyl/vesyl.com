<?php
if (!function_exists('cachePut')) {
    /**
     * Store data into cache
     * @param $key
     * @param $value
     * @param int $minutes
     * @since 15-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function cachePut($key, $value, $minutes = 10)
    {
        if (!Cache::has(md5($key))) {
            if (count(explode('::', $key)) > 1 && explode('::', $key)[0] != '') {
                $groupName = explode('::', $key)[0];
                if (!Cache::has(md5($groupName))) {
                    Cache::put(md5($groupName), json_encode(array(md5($key))), $minutes);
                    Cache::put(md5($key), $value, $minutes);
                } else {
                    $groupValues = json_decode(Cache::get(md5($groupName)));
                    if (!in_array(md5($key), $groupValues)) {
                        array_push($groupValues, md5($key));
                        Cache::put(md5($groupName), json_encode($groupValues), $minutes);
                        Cache::put(md5($key), $value, $minutes);
                    }
                }
            } else {
                Cache::put(md5($key), $value, $minutes);
            }
        }
    }
}
if (!function_exists('cacheForever')) {
    /**
     * Store data into cache forever
     * @param $key
     * @param $value
     * @since 18-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function cacheForever($key, $value)
    {
        if (!Cache::has(md5($key))) {
            if (count(explode('::', $key)) > 1 && explode('::', $key)[0] != '') {
                $groupName = explode('::', $key)[0];
                if (!Cache::has(md5($groupName))) {
                    Cache::forever(md5($groupName), json_encode(array(md5($key))));
                    Cache::forever(md5($key), $value);
                } else {
                    $groupValues = json_decode(Cache::get(md5($groupName)));
                    if (!in_array(md5($key), $groupValues)) {
                        array_push($groupValues, md5($key));
                        Cache::forever(md5($groupName), json_encode($groupValues));
                        Cache::forever(md5($key), $value);
                    }
                }
            } else {
                Cache::forever(md5($key), $value);
            }
        }
    }
}
if (!function_exists('cacheGet')) {
    /**
     * Get data from cache
     * @param $key
     * @return bool|array|object
     * @since 15-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function cacheGet($key)
    {
        if (Cache::has(md5($key))) return Cache::get(md5($key));
        return false;
    }
}
if (!function_exists('cacheClearByKey')) {
    /**
     * Clear cache by key
     * @param $key
     * @since 16-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function cacheClearByKey($key)
    {
        if (Cache::has(md5($key))) Cache::forget(md5($key));
    }
}
if (!function_exists('cacheClearByGroupNames')) {
    /**
     * Clear cache by group names
     * @param array|string $groupNames Group names to be cleared from cache data
     * @since 16-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function cacheClearByGroupNames($groupNames)
    {
        if (is_array($groupNames)) {
            foreach ($groupNames as $groupName) {
                if (Cache::has(md5($groupName))) {
                    $groupValues = json_decode(Cache::get(md5($groupName)));
                    foreach ($groupValues as $groupValue) {
                        if (Cache::has($groupValue)) Cache::forget($groupValue);
                    }
                    Cache::forget(md5($groupName));
                }
            }
        } else {
            if (Cache::has(md5($groupNames))) {
                $groupValues = json_decode(Cache::get(md5($groupNames)));
                foreach ($groupValues as $groupValue) {
                    if (Cache::has($groupValue)) Cache::forget($groupValue);
                }
                Cache::forget(md5($groupNames));
            }
        }
    }
}
if (!function_exists('cacheClearByTableNames')) {
    /**
     * Clear cache by table names
     * @param array|string $tableNames Array of table names or a table name
     * @since 16-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function cacheClearByTableNames($tableNames)
    {
        if (is_array($tableNames)) {
            foreach ($tableNames as $tableName) {
                if (Cache::has(md5($tableName))) {
                    $tableNameValues = json_decode(Cache::get(md5($tableName)));
                    foreach ($tableNameValues as $tableNameValue) {
                        if (Cache::has($tableNameValue)) Cache::forget($tableNameValue);
                    }
                    Cache::forget(md5($tableName));
                }
            }
        } else {
            if (Cache::has(md5($tableNames))) {
                $tableNameValues = json_decode(Cache::get(md5($tableNames)));
                foreach ($tableNameValues as $tableNameValue) {
                    if (Cache::has($tableNameValue))
                        Cache::forget($tableNameValue);
                }
                Cache::forget(md5($tableNames));
            }
        }
    }
}

if (!function_exists('getSetting')) {
    /**
     * Get setting value and cache the value for a day
     * @param string $settingObject
     * @return mixed
     * @throws Exception
     * @since 19-01-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function getSetting($settingObject)
    {
        $settingValue = new stdClass();
        switch ($settingObject) {
            case 'price_symbol':
                $objCurrencyModel = \FlashSale\Http\Modules\Admin\Models\Currency::getInstance();
                $whereForPrice = ['rawQuery' => 'is_primary=? AND status=?', 'bindParams' => ['Y', 1]];
                $selectedColumns = ['symbol'];
                $cacheKey = "settings_objects::" . implode('-', array_flatten($whereForPrice));
                $priceSymbol = new stdClass();
                $priceSymbol->symbol = "$";
                if (cacheGet($cacheKey)) {
                    $priceSymbol = cacheGet($cacheKey);
                } else {
                    $priceSymbol = $objCurrencyModel->getCurrencyWhere($whereForPrice, $selectedColumns);
                    cachePut($cacheKey, $priceSymbol, 86400);
                }
//                dd($priceSymbol);
                $settingValue = $priceSymbol->symbol;
                break;
            default:
                $objSettingObject = \FlashSale\Http\Modules\Admin\Models\SettingsObject::getInstance();
                $whereForSettingObject = ['rawQuery' => 'name=?', 'bindParams' => [$settingObject]];
                $selectedColumns = ['value'];

                $cacheKey = "settings_objects::" . implode('-', array_flatten($whereForSettingObject));
                if (cacheGet($cacheKey)) {
                    $settingValue = cacheGet($cacheKey);
                } else {
                    $settingValue = $objSettingObject->getSettingObjectWhere($whereForSettingObject, $selectedColumns);
                    cachePut($cacheKey, $settingValue, 86400);
                }
                $settingValue = $settingValue->value;
                break;
        }
        return $settingValue;
    }
}

if (!function_exists('uploadImageToStoragePath')) {
    /**
     * Upload image to storage path
     * @param $image
     * @param null $folderName Folder name (mainFolder_subFolder_subSubFolder)
     * @param null $fileName
     * @param int $imageWidth
     * @param int $imageHeight
     * @return bool|string
     * @since 02-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function uploadImageToStoragePath($image, $folderName = null, $fileName = null, $imageWidth = 1024, $imageHeight = 1024)
    {
        $destinationFolder = 'uploads/';
        if ($folderName != '') {
            $folderNames = explode('_', $folderName);
            $folderPath = implode('/', array_map(function ($value) {
                return $value;
            }, $folderNames));
            $destinationFolder .= $folderPath . '/';
        }
        $destinationPath = storage_path($destinationFolder);
        if (!File::exists($destinationPath)) File::makeDirectory($destinationPath, 0777, true, true);
        $filename = ($fileName != '') ? $fileName : $folderName . '_' . time() . '.jpg';
        $imageResult = Image::make($image)->resize($imageWidth, $imageHeight, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . $filename, imageQuality($image));
        if ($imageResult) return '/image/' . $filename;
        return false;
    }
}

//if (!function_exists('filegetcontents')) {
//    /**
//     * Upload image to storage path
//     * @param $image
//     * @param null $folderName Folder name (mainFolder_subFolder_subSubFolder)
//     * @param null $fileName
//     * @param int $imageWidth
//     * @param int $imageHeight
//     * @return bool|string
//     * @since 02-02-2016
//     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
//     */
//    function filegetcontents($txt, $folderName = null, $fileName = null)
//    {
//        $destinationFolder = 'uploads/';
//        if ($folderName != '') {
//            $folderNames = explode('_', $folderName);
//            $folderPath = implode('/', array_map(function ($value) {
//                return $value;
//            }, $folderNames));
//            $destinationFolder .= $folderPath . '/';
//        }
//        $val = $data['page_content_url'];
//        $description = $data['page'];
//        $destinationPath = "../storage/pages";
//        if (!File::exists($destinationPath)) File::makeDirectory($destinationPath, 0777, true, true);
//        $editpolicyfile = fopen("$destinationPath/$val.txt", "w") or die("Unable to open file!");
//        $txt = $description;
//        fwrite($editpolicyfile, $txt);
//        fclose($editpolicyfile);
//        $insertData['page_name'] = $data['page_title'];
//        $insertData['page_title'] = $data['page_title'];
//        $insertData['page_content_url'] = "$destinationPath/$val.txt";
//        if ($imageResult) return '/image/' . $filename;
//        return false;
//    }
//}

if (!function_exists('imageQuality')) {
    /**
     * Get image quality for compression
     * @param $image
     * @return int
     * @since 02-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function imageQuality($image)
    {
        try {
            $imageSize = filesize($image) / (1024 * 1024);
            if ($imageSize < 0.5) return 70;
            elseif ($imageSize > 0.5 && $imageSize < 1) return 60;
            elseif ($imageSize > 1 && $imageSize < 2) return 50;
            elseif ($imageSize > 2 && $imageSize < 5) return 40;
            elseif ($imageSize > 5) return 30;
            else return 50;
        } catch (\Exception $e) {
            return 50;
        }
    }
}


if (!function_exists('deleteImageFromStoragePath')) {
    /**
     * Delete an image from storage path
     * @param $fileName Name of the image (Ex. category_2_1432423423.jpg)
     * @return int
     * @since 03-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function deleteImageFromStoragePath($fileName)
    {
        if ($fileName != '') {
            $folderNames = explode('_', explode('/', $fileName)[2]);
            $filePath = '/uploads/';
            switch ($folderNames[0]) {
                case 'product': //product_14_0_1456562271.jpg
                    $filePath .= $folderNames[0] . '/' . $folderNames[1] . '/' . explode('/', $fileName)[2];
                    break;
                default://folderName_id_timeStamp.jpg
                    $filePath .= $folderNames[0] . '/' . explode('/', $fileName)[2];
                    break;
            }
            return (\Illuminate\Support\Facades\File::delete(storage_path() . $filePath));
        }
    }
}

if (!function_exists('print_a')) {
    /**
     * Print human-readable information about a variable and stop execution(die)
     * @param $data
     * @since 08-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    function print_a($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('object_to_array')) {

    /**
     * Convert an object to array
     * @param $obj
     * @return array
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 10-08-2016
     */
    function object_to_array($obj)
    {
        if (is_object($obj)) $obj = (array)$obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = object_to_array($val);
            }
        } else $new = $obj;
        return $new;
    }
}

if (!function_exists('multidimensional_array_search')) {
    /**
     * Search an element from multidimensional array
     * @param $search_value
     * @param $array
     * @return mixed
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 10-08-2016
     */
    function multidimensional_array_search($search_value, $array)
    {
        $mached = array();
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                if (count($value) > 0) {
                    multidimensional_array_search($search_value, $value);
                } else {
                    return array_search($search_value, $array);
                    exit;
                }
            }
        }
    }
}

//if (!function_exists('displayImageFromStoragePath')) {
//    function displayImageFromStoragePath($fileName)
//    {
//        $filePath = explode("_", $fileName);
//        $folderPath = '';
//        switch ($filePath[0]) {
//            case 'product'://product_14_0_1456562271.jpg
//                $folderPath = $filePath[0] . '/' . $filePath[1];
//                break;
//            default://folderName_id_timeStamp.jpg
//                $folderPath = $filePath[0];
//                break;
//        }
//        $path = storage_path() . '/uploads/' . $folderPath . '/' . $fileName;
//        $file = File::get($path);
//        $type = File::mimeType($path);
//        $response = Response::make($file, 200);
//        $response->header("Content-Type", $type);
//        return $response;
//    }
//}


if (!function_exists('checkSubdomain')) {

    function checkSubdomain($urlmain)
    {
        $domain = env("WEB_URL");
        $shopurl = explode("." . $domain, $urlmain);
        $shopname = $shopurl[0];
        $link = mysqli_connect(env("DB_HOST"), env("DB_USERNAME"), env("DB_PASSWORD"),"vesyl");
//        mysqli_select_db($link, "vesyl");
        $sql = "select * from shops where shop_name = '$shopname'";
//        $result =  mysqli_query($link, $sql);
        if($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                return $shopname;
            }
            else{
                return 401;
            }
        }
    }

}

if (!function_exists('createDB')) {

    function createDB($dbname)
    {
//        $link = mysqli_connect(env("DB_HOST"), env("DB_USERNAME"), env("DB_PASSWORD") );
//        $sql = "CREATE DATABASE $dbname";
//
//        mysqli_query($link, $sql);
//        mysqli_select_db($link, $dbname);
//        $query = file_get_contents("vesylstr.sql");
//        if (mysqli_multi_query($link, $query)) {
//            do {
//                if (!mysqli_more_results($link)) {
//                   break; //printf("-----------------\n");
//                }
//            } while (mysqli_next_result($link));
//            mysqli_close($link);
//            return 200;
//        }
//        else {
//            mysqli_close($link);
//            return 400;
//        }

        // using statement

        DB::statement("create database $dbname");
        DB::statement("create table $dbname.admin_gift_certificate like vesyl.admin_gift_certificate");
        DB::statement("create table $dbname.banners like vesyl.banners");
        DB::statement("create table $dbname.campaigns like vesyl.campaigns");
        DB::statement("create table $dbname.currencies select * from vesyl.currencies");
        DB::statement("create table $dbname.filter_product_relation like vesyl.filter_product_relation");
        DB::statement("create table $dbname.gift_certificates like vesyl.gift_certificates");
        DB::statement("create table $dbname.languages like vesyl.languages");
        DB::statement("create table $dbname.language_values like vesyl.language_values");
        DB::statement("create table $dbname.location select * from vesyl.location");
        DB::statement("create table $dbname.mail_templates like vesyl.mail_templates");
        DB::statement("create table $dbname.migrations like vesyl.migrations");
        DB::statement("create table $dbname.newsletters like vesyl.newsletters");
        DB::statement("create table $dbname.newsletter_log like vesyl.newsletter_log");
        DB::statement("create table $dbname.notification like vesyl.notification");
        DB::statement("create table $dbname.orders like vesyl.orders");
        DB::statement("create table $dbname.pages_extra like vesyl.pages_extra");
        DB::statement("create table $dbname.password_resets like vesyl.password_resets");
        DB::statement("create table $dbname.payment_creds like vesyl.payment_creds");
        DB::statement("create table $dbname.payment_methods like vesyl.payment_methods");
        DB::statement("create table $dbname.permissions like vesyl.permissions");
        DB::statement("create table $dbname.permission_user_relation like vesyl.permission_user_relation");
        DB::statement("create table $dbname.productmeta like vesyl.productmeta");
        DB::statement("create table $dbname.products like vesyl.products");
        DB::statement("create table $dbname.product_brands like vesyl.product_brands");
        DB::statement("create table $dbname.product_categories like vesyl.product_categories");
        DB::statement("create table $dbname.product_features like vesyl.product_features");
        DB::statement("create table $dbname.product_feature_variants like vesyl.product_feature_variants");
        DB::statement("create table $dbname.product_feature_variant_relation like vesyl.product_feature_variant_relation");
        DB::statement("create table $dbname.product_filter_option like vesyl.product_filter_option");
        DB::statement("create table $dbname.product_images like vesyl.product_images");
        DB::statement("create table $dbname.product_options like vesyl.product_options");
        DB::statement("create table $dbname.product_option_variants like vesyl.product_option_variants");
        DB::statement("create table $dbname.product_option_variants_combination like vesyl.product_option_variants_combination");
        DB::statement("create table $dbname.product_option_variant_relation like vesyl.product_option_variant_relation");
        DB::statement("create table $dbname.reviews like vesyl.reviews");
        DB::statement("create table $dbname.rewards like vesyl.rewards");
        DB::statement("create table $dbname.reward_settings like vesyl.reward_settings");
        DB::statement("create table $dbname.settings_descriptions select * from vesyl.settings_descriptions");
        DB::statement("create table $dbname.settings_objects select * from vesyl.settings_objects");
        DB::statement("create table $dbname.settings_sections select * from vesyl.settings_sections");
        DB::statement("create table $dbname.settings_variants select * from vesyl.settings_variants");
        DB::statement("create table $dbname.shops like vesyl.shops");
        DB::statement("create table $dbname.shops_metadata like vesyl.shops_metadata");
        DB::statement("create table $dbname.taxes like vesyl.taxes");
        DB::statement("create table $dbname.transactions like vesyl.transactions");
        DB::statement("create table $dbname.users like vesyl.users");
        DB::statement("create table $dbname.usersmeta like vesyl.usersmeta");
        return;
    }
}

?>