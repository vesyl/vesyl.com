<?php

namespace FlashSale\Http\Modules\Supplier\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Image;
use Validator;
use Input;
use Redirect;
use File;
use Excel;

use FlashSale\Http\Modules\Supplier\Models\User;
use FlashSale\Http\Modules\Supplier\Models\Usersmeta;

use Yajra\Datatables\Datatables;

//Models start

use FlashSale\Http\Modules\Supplier\Models\ProductCategory;
use FlashSale\Http\Modules\Supplier\Models\Products;
use FlashSale\Http\Modules\Supplier\Models\ProductFeatures;
use FlashSale\Http\Modules\Supplier\Models\ProductMeta;
use FlashSale\Http\Modules\Supplier\Models\ProductImage;
use FlashSale\Http\Modules\Supplier\Models\ProductOption;
use FlashSale\Http\Modules\Supplier\Models\ProductOptionVariant;
use FlashSale\Http\Modules\Supplier\Models\ProductOptionVariantRelation;
use FlashSale\Http\Modules\Supplier\Models\ProductFeatureVariantRelation;
use FlashSale\Http\Modules\Supplier\Models\ProductOptionVariantsCombination;
use FlashSale\Http\Modules\Supplier\Models\Shop;

//Models end


use Illuminate\Support\Facades\Session;


class ProductController extends Controller
{

    public function addProduct(Request $request)
    {

        /*OUTPUT BUFFERING TEST FOR BROWSER CLOSE BY USER START*/
        /* ob_end_clean();
        header("Connection: close\r\n");
        header("Content-Encoding: none\r\n");
        ignore_user_abort(true); // optional
        ob_start();
        echo ('Text user will see');
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();     // Strange behaviour, will not work
        flush();            // Unless both are called !
        ob_end_clean();
//do processing here
        sleep(5);
        $objFeatureVariantsModel = ProductFeatureVariants::getInstance();
        $dataAddFV = array('variant_name' => "OB test", 'description' => "OB test description", 'feature_id' => "10000");
        $result = $objFeatureVariantsModel->addFeatureVariant($dataAddFV);
        echo('Text user will never see');
//do some processing
        die; */
        /*OUTPUT BUFFERING TEST FOR BROWSER CLOSE BY USER END*/

        //todo change where 1 to added_by
        $objModelCategory = ProductCategory::getInstance();
        $objModelFeatures = ProductFeatures::getInstance();
        $objModelProducts = Products::getInstance();
        $objModelProductMeta = ProductMeta::getInstance();
        $objModelProductImage = ProductImage::getInstance();
        $objModelProductOptionVariant = ProductOptionVariant::getInstance();
        $objModelProductOptionVariantRelation = ProductOptionVariantRelation::getInstance();
        $objModelProductFeatureVariantRelation = ProductFeatureVariantRelation::getInstance();
        $objModelProductOptVarCombination = ProductOptionVariantsCombination::getInstance();
        $objModelShops = Shop::getInstance();

        $userId = Session::get('fs_supplier')['id'];

        $whereForCat = ['rawQuery' => 'category_status = ? and (created_by = ?)', 'bindParams' => [1, $userId]];
        $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);

        $whereForFeatureGroup = ['rawQuery' => 'group_flag =? and status = ? and (added_by = ?)', 'bindParams' => [1, 1, $userId]];
        $allFeatureGroups = $objModelFeatures->getAllFeaturesWhere($whereForFeatureGroup);

        $objModelProductOption = ProductOption::getInstance();
        $whereForOptions = ['rawQuery' => 'status = 1 and (added_by = ?)', 'bindParams' => [$userId]];
        $allOptions = $objModelProductOption->getAllOptionsWhere($whereForOptions);
//        print_a($allOptions);

        $whereForShops = ['rawQuery' => "shop_status IN (1,2) and parent_category_id = 0 and user_id = $userId"];
        $selColsForShops = ['shops.*', 'users.name', 'users.last_name', 'users.username', 'users.status'];
        $resultAllShops = json_decode($objModelShops->getAllShopsWhere($whereForShops, $selColsForShops), true);

        if ($request->isMethod('post')) {
//            $inputData = $request->input('product_data');//Excludes image
            $inputData = $request->all()['product_data'];//Includes image
//            dd($inputData);

//            print_a($inputData['options']);
//            print_a($_FILES);

            $rules = [
                'product_name' => 'required',
                'category_id' => 'required',
                'price' => 'required',
                'in_stock' => 'required',
                'comment' => 'max:100',
//                'shop_id' => 'required|integer|min:0',
                'mainimage' => 'required|image|mimes:jpeg,bmp,png|max:1000'
            ];

            $messages['mainimage.required'] = 'Please select a main image for the product.';
            $validator = Validator::make($inputData, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {

                DB::beginTransaction();
                $errors = array();
                $productData = array();
                $productData['product_name'] = trim($inputData['product_name']);
//                $productData['for_shop_id'] = $inputData['shop_id'];//todo check if the shop belongs to the supplier
                if (array_key_exists('product_type', $inputData))
                    $productData['product_type'] = 1;
                $productData['min_qty'] = $inputData['minimum_order_quantity'];
                $productData['max_qty'] = $inputData['maximum_order_quantity'];
                $productData['category_id'] = $inputData['category_id'];
                $productData['for_gender'] = $inputData['for_gender'];
                $productData['price_total'] = $inputData['price'];
                $productData['list_price'] = $inputData['list_price'];
                $productData['in_stock'] = $inputData['in_stock'];
                $productData['added_date'] = time();
                $productData['added_by'] = $userId;
                $productData['product_status'] = 0;
                $productData['status_set_by'] = $userId;

                $insertedProductId = $objModelProducts->addNewProduct($productData);
                if ($insertedProductId > 0) {
                    //--------------------------PRODUCT-METADATA----------------------------//
                    $productMetaData['product_id'] = $insertedProductId;
                    $productMetaData['full_description'] = trim($inputData['full_description']);
                    $productMetaData['short_description'] = trim($inputData['short_description']);

                    //------------------------PRODUCT OPTIONS START HERE---------------------//
                    if (array_key_exists('options', $inputData)) {
                        $finalOptionVariantRelationData = array();
                        $varDataForCombinations = array();
                        foreach ($inputData['options'] as $key => $optionValue) {
                            $optionVariantRelationData['product_id'] = $insertedProductId;
                            $optionVariantRelationData['option_id'] = $optionValue['option_id'];
                            $optionVariantRelationData['status'] = $optionValue['status'];

                            $tempOptionVariantData = array();
                            $variantIds = array();
                            //-------------------------OLD OPTION VARIANT START-----------------------//
                            /*
                            if (array_key_exists('variantData', $optionValue)) {
                                foreach ($optionValue['variantData'] as $variantKey => $variantValue) {
                                    $temp = array();
                                    if ($variantValue['variant_id'] == 0) {
                                        $variantData['option_id'] = $optionValue['option_id'];
                                        $variantData['variant_name'] = $variantValue['variant_name'];
                                        $variantData['added_by'] = $userId;
                                        $variantData['status'] = $variantValue['status'];
                                        $variantData['created_at'] = NULL;

                                        $insertedVariantId = $objModelProductOptionVariant->addNewVariantAndGetID($variantData);
                                        if ($insertedVariantId > 0) {
                                            array_push($variantIds, $insertedVariantId);
                                            $temp['VID'] = $insertedVariantId;
                                            $temp['VN'] = $variantValue['variant_name'];
                                            $temp['PM'] = $variantValue['price_modifier'];
                                            $temp['PMT'] = $variantValue['price_modifier_type'];
                                            $temp['WM'] = $variantValue['weight_modifier'];
                                            $temp['WMT'] = $variantValue['weight_modifier_type'];
                                            $temp['STTS'] = $variantValue['status'];
                                        }
                                    } else {
                                        array_push($variantIds, $variantValue['variant_id']);
                                        $temp['VID'] = $variantValue['variant_id'];
                                        $temp['VN'] = $variantValue['variant_name'];
                                        $temp['PM'] = $variantValue['price_modifier'];
                                        $temp['PMT'] = $variantValue['price_modifier_type'];
                                        $temp['WM'] = $variantValue['weight_modifier'];
                                        $temp['WMT'] = $variantValue['weight_modifier_type'];
                                        $temp['STTS'] = $variantValue['status'];
                                    }
                                    $tempOptionVariantData[] = $temp;
                                }
                                if (!empty($variantIds) && !empty($tempOptionVariantData)) {
                                    $optionVariantRelationData['variant_ids'] = implode(',', $variantIds);
                                    $optionVariantRelationData['variant_data'] = json_encode($tempOptionVariantData);
                                }
                            }
                            */
                            //-------------------------OLD OPTION VARIANT END-----------------------//

                            //-------------------------NEW OPTION VARIANT START---------------------//
                            //todo need validation that there is atleast one variant in every option
                            //todo med:med 17/12/2016 need validation that atleast one of the options contain more than one variants // check if this validation is required
                            //todo high:diff validation that variant_name, price_modifier, weight_modifier cannot be empty
                            if (array_key_exists('variantData', $optionValue)) {
                                foreach ($optionValue['variantData'] as $variantKey => $variantValue) {
                                    $temp = array();
                                    array_push($variantIds, $variantValue['variant_id']);
                                    $temp['VID'] = $variantValue['variant_id'];
                                    $temp['VN'] = $variantValue['variant_name'];
                                    $temp['PM'] = $variantValue['price_modifier'];
                                    $temp['PMT'] = $variantValue['price_modifier_type'];
                                    $temp['WM'] = $variantValue['weight_modifier'];
                                    $temp['WMT'] = $variantValue['weight_modifier_type'];
                                    $temp['STTS'] = $variantValue['status'];
                                    $tempOptionVariantData[] = $temp;
                                }
                            }
                            if (array_key_exists('variantDataNew', $optionValue)) {
                                foreach ($optionValue['variantDataNew'] as $variantKey => $variantValue) {
                                    $temp = array();
                                    $variantData['option_id'] = $optionValue['option_id'];
                                    $variantData['variant_name'] = $variantValue['variant_name'];
                                    $variantData['added_by'] = $userId;
                                    $variantData['status'] = $variantValue['status'];
                                    $variantData['created_at'] = NULL;
                                    $insertedVariantId = $objModelProductOptionVariant->addNewVariantAndGetID($variantData);
                                    if ($insertedVariantId > 0) {
                                        $varDataForCombinations[$variantValue['variant_id']] = $insertedVariantId;
                                        array_push($variantIds, $insertedVariantId);
                                        $temp['VID'] = $insertedVariantId;
                                        $temp['VN'] = $variantValue['variant_name'];
                                        $temp['PM'] = $variantValue['price_modifier'];
                                        $temp['PMT'] = $variantValue['price_modifier_type'];
                                        $temp['WM'] = $variantValue['weight_modifier'];
                                        $temp['WMT'] = $variantValue['weight_modifier_type'];
                                        $temp['STTS'] = $variantValue['status'];
                                    }
                                    $tempOptionVariantData[] = $temp;
                                }
                            }
                            if (!empty($variantIds) && !empty($tempOptionVariantData)) {
                                $optionVariantRelationData['variant_ids'] = implode(',', $variantIds);
                                $optionVariantRelationData['variant_data'] = json_encode($tempOptionVariantData);
                            }
                            //-------------------------NEW OPTION VARIANT END---------------------//

                            $finalOptionVariantRelationData[] = $optionVariantRelationData;
                        }
                        if (!empty($finalOptionVariantRelationData)) {
                            $objModelProductOptionVariantRelation->addNewOptionVariantRelation($finalOptionVariantRelationData);
                        }

                        //------------------------PRODUCT OPTION COMBINATIONS START HERE---------------------//
                        if (count($inputData['options']) > 1) {
                            if (array_key_exists('opt_combination', $inputData)) {
                                //todo high:diff validation required [call generateCombinations & compare data from view]
                                foreach ($inputData['opt_combination']['existing'] as $keyCombination => $valueCombination) {
                                    //TODO ADD BARCODE, shipping info and image data for the combination here
                                    $dataCombinations['product_id'] = $insertedProductId;
                                    $dataCombinations['variant_ids'] = $keyCombination;
                                    $dataCombinations['quantity'] = $valueCombination['quantity'];
                                    $dataCombinations['exception_flag'] = 0;
                                    if (isset($valueCombination['excludeflag']) && $valueCombination['excludeflag'] == 'on') {
                                        $dataCombinations['exception_flag'] = 1;
                                    }
                                    $objModelProductOptVarCombination->addNewOptionVariantsCombination($dataCombinations);
                                }
                                if (array_key_exists('new', $inputData['opt_combination'])) {
                                    foreach ($inputData['opt_combination']['new'] as $keyCombination => $valueCombination) {
                                        $flags = explode("_", $valueCombination['newflag']);
                                        $combinationVarIds = explode("_", $keyCombination);
                                        $flagKeys = array_keys($flags, "1");
                                        if (!empty($flagKeys)) {
                                            foreach ($flagKeys as $keyFK => $valueFK) {
                                                $combinationVarIds[$keyFK] = $varDataForCombinations[$combinationVarIds[$keyFK]];
                                            }
                                        }
                                        //TODO low:easy ADD BARCODE, shipping info and image data for the combination here
                                        $dataCombinations['product_id'] = $insertedProductId;
                                        $dataCombinations['variant_ids'] = implode("_", $combinationVarIds);
                                        $dataCombinations['quantity'] = $valueCombination['quantity'];
                                        $dataCombinations['exception_flag'] = 0;
                                        //todo med:med add code for image for combination here
                                        if (isset($valueCombination['excludeflag']) && $valueCombination['excludeflag'] == 'on') {
                                            $dataCombinations['exception_flag'] = 1;
                                        }
                                        $objModelProductOptVarCombination->addNewOptionVariantsCombination($dataCombinations);
                                    }
                                }
                            } else {
                                DB::rollBack();
                                return Redirect::back()
                                    ->with(["status" => 'error', 'msg' => 'Something went wrong while generating combinations. Please try again.'])
                                    ->withErrors($validator)
                                    ->withInput();
                            }
                        }
                        //------------------------PRODUCT OPTION COMBINATIONS END HERE---------------------//
                    }
                    //------------------------PRODUCT OPTIONS END HERE---------------------//

                    $productMetaData['weight'] = $inputData['shipping_properties']['weight'];
                    $productMetaData['shipping_freight'] = $inputData['shipping_properties']['shipping_freight'];

                    $shippingParams = array();
                    $shippingParams['min_items'] = $inputData['shipping_properties']['min_items'];
                    $shippingParams['max_items'] = $inputData['shipping_properties']['min_items'];

                    if (array_key_exists('box_length', $inputData['shipping_properties']))
                        $shippingParams['box_length'] = $inputData['shipping_properties']['box_length'];
                    if (array_key_exists('box_width', $inputData['shipping_properties']))
                        $shippingParams['box_width'] = $inputData['shipping_properties']['box_width'];
                    if (array_key_exists('box_height', $inputData['shipping_properties']))
                        $shippingParams['box_height'] = $inputData['shipping_properties']['box_height'];

                    $productMetaData['shipping_params'] = json_encode($shippingParams);
                    $productMetaData['quantity_discount'] = json_encode($inputData['quantity_discount']);
                    $productMetaData['product_tabs'] = json_encode($inputData['product_tabs']);

                    $insertedProductMetaId = $objModelProductMeta->addProductMetaData($productMetaData);
                    if (!$insertedProductMetaId) {
                        $errors[] = 'Sorry, some of the product data could not be added, please try again. If you get this message repeatedly, please raise a ticket to the site admin.';
                        goto blockRollBack;
                    }
                    //--------------------------END PRODUCT-METADATA----------------------------//


                    //----------------------------PRODUCT-IMAGES------------------------------//
                    $productImages = $_FILES['product_data'];
                    $imageData = array();
                    if ($productImages['error']['mainimage'] == 0) {
                        $mainImageURL = uploadImageToStoragePath($productImages['tmp_name']['mainimage'], 'product_' . $insertedProductId, 'product_' . $insertedProductId . '_0_' . time() . '.jpg', 724, 1024);
                        if ($mainImageURL) {
                            $mainImageData['for_product_id'] = $insertedProductId;
                            $mainImageData['image_type'] = 0;
                            $mainImageData['image_upload_type'] = 0;
                            $mainImageData['image_url'] = $mainImageURL;
                            $imageData[] = $mainImageData;
                        }
                    } else {
                        $errors[] = 'Sorry, something went wrong. Main image could not be uploaded, You can upload it on edit section.';
                    }

                    if (array_key_exists('otherimages', $productImages['name'])) {
                        foreach ($productImages['tmp_name']['otherimages'] as $otherImageKey => $otherImage) {
                            if ($otherImage != '') {
                                $otherImageURL = uploadImageToStoragePath($otherImage, 'product_' . $insertedProductId, 'product_' . $insertedProductId . '_' . ($otherImageKey + 1) . '_' . time() . '.jpg', 724, 1024);
                                if ($otherImageURL) {
                                    $otherImageData['for_product_id'] = $insertedProductId;
                                    $otherImageData['image_type'] = 1;
                                    $otherImageData['image_upload_type'] = 0;
                                    $otherImageData['image_url'] = $otherImageURL;
                                    $imageData[] = $otherImageData;
                                }
                            }
                        }
                    }
                    if (!empty($imageData)) {
                        $objModelProductImage->addNewImage($imageData);
                    }
                    //--------------------------END PRODUCT-IMAGES----------------------------//

                    //------------------------PRODUCT FEATURES START HERE---------------------//
                    if (array_key_exists('features', $inputData)) {
                        $productDataFeatures = $inputData['features'];
                        $fvrDataToInsert = array();
                        foreach ($productDataFeatures as $keyPDF => $valuePDF) {
                            if (array_key_exists("single", $productDataFeatures[$keyPDF])) {
//                            $fvrDataToInsert[] = ['product_id' => $insertedProductId, 'feature_id' => $keyPDF, 'variant_ids' => 0, 'display_status' => $productDataFeatures[$keyPDF]['status']];
                                $objModelProductFeatureVariantRelation->addFeatureVariantRelation(['product_id' => $insertedProductId, 'feature_id' => $keyPDF, 'variant_ids' => 0, 'display_status' => $productDataFeatures[$keyPDF]['status']]);
                            } else if (array_key_exists("multiple", $productDataFeatures[$keyPDF])) {
//                            $fvrDataToInsert[] = ['product_id' => $insertedProductId, 'feature_id' => $keyPDF, 'variant_ids' => implode(",", array_keys($valuePDF['multiple'])), 'display_status' => $valuePDF['status']];
                                $objModelProductFeatureVariantRelation->addFeatureVariantRelation(['product_id' => $insertedProductId, 'feature_id' => $keyPDF, 'variant_ids' => implode(",", array_keys($valuePDF['multiple'])), 'display_status' => $valuePDF['status']]);
                            } else if (array_key_exists("select", $productDataFeatures[$keyPDF])) {
//                            $fvrDataToInsert[] = ['product_id' => $insertedProductId, 'feature_id' => $keyPDF, 'variant_ids' => $valuePDF['select'], 'display_status' => $valuePDF['status']];
                                $objModelProductFeatureVariantRelation->addFeatureVariantRelation(['product_id' => $insertedProductId, 'feature_id' => $keyPDF, 'variant_ids' => "" . $valuePDF['select'], 'display_status' => $valuePDF['status']]);
                            }
                        }
//                    $objModelProductFeatureVariantRelation->addFeatureVariantRelation($fvrDataToInsert);
                    }
                    //------------------------PRODUCT FEATURES END HERE---------------------//

                    //------------------------PRODUCT FILTERS START HERE---------------------//
                    if (array_key_exists('filter', $inputData)) {
                        //todo high:med filters here

                    }
                    //------------------------PRODUCT FILTERS END HERE---------------------//


                }

                if ($insertedProductId && isset($insertedProductMetaId)) {
                    DB::commit();
                    return Redirect::back()->with(['status' => 'success', 'msg' => 'New product "' . $productData['product_name'] . '" has been added.']);
                } else {
                    blockRollBack:{
                        DB::rollBack();
                        return Redirect::back()->with(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }
                }
            }
        }
        foreach ($allCategories as $key => $value) {
            $allCategories[$key]->display_name = $this->getCategoryDisplayName($value->category_id);
        }
        return view('Supplier/Views/product/addProduct', ['code' => '', 'allCategories' => $allCategories, 'allOptions' => $allOptions, 'featureGroups' => json_decode($allFeatureGroups, true), 'allShops' => $resultAllShops]);

        /*
        return view("Supplier/Views/product/addProduct");
        */
    }

    public function getCategoryDisplayName($id)
    {
        if ($id == 0) {
            return '';
        } else {
            $objCategoryModel = ProductCategory::getInstance();
            $whereForCat = ['rawQuery' => 'category_id =?', 'bindParams' => [$id]];
            $parentCategory = $objCategoryModel->getCategoryDetailsWhere($whereForCat);
            if ($parentCategory->parent_category_id != 0) {
                return $this->getCategoryDisplayName($parentCategory->parent_category_id) . '&brvbar;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            } else {
                return '';
            }
        }
    }

    public function manageProducts()
    {
        //here
        $objCategoryModel = ProductCategory::getInstance();
        $userId = Session::get('fs_supplier')['id'];

        $where = ['rawQuery' => 'created_by = ? or created_by IN(' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ')', 'bindParams' => [$userId]];
        $allactivecategories = $objCategoryModel->getAllCategoriesWhere($where);
        return view('Supplier/Views/product/manageProducts', ['allCategories' => $allactivecategories]);
    }


    public function productListAjaxHandler(Request $request, $method)
    {

        $inputData = $request->input();
        $objModelProducts = Products::getInstance();
        $objCategoryModel = ProductCategory::getInstance();
        $supplierId = Session::get('fs_supplier')['id'];
        switch ($method) {

            case 'manageProducts':
                //   Modify code for filter //
                $iTotalRecords = $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                $columns = array('products.product_id', 'products.added_date', 'products.product_type', 'users.username', 'shops.shop_name', 'products.product_name', 'products.price_total', 'products.list_price', 'products.min_qty', 'products.max_qty', 'products.category_id', 'products.available_countries', 'products.products_status');
                $sortingOrder = "";
                if (isset($_REQUEST['order'])) {
                    $sortingOrder = $columns[$_REQUEST['order'][0]['column']];
                }
                if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {

                    if ($_REQUEST['customActionValue'] != '' && !empty($_REQUEST['productId'])) {

                        $statusData['product_status'] = $_REQUEST['customActionValue'];
                        $whereForStatusUpdate = ['rawQuery' => 'product_id IN (' . implode(',', $_REQUEST['productId']) . ')'];
                        $updateResult = $objModelProducts->updateProductWhere($statusData, $whereForStatusUpdate);
                        if ($updateResult) {
                            //NOTIFICATION TO USER FOR ORDER STATUS CHANGE
                            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                            $records["customActionMessage"] = "Group action successfully has been completed."; // pass custom message(useful for getting status of group actions)
                        }
                    }
                }
                // End Modify code for filter//
                // NORMAL DATATABLE CODE STARTS//
                $where = ['rawQuery' => 'products.product_type = ? AND products.product_status IN (0,1,2,3,4) AND product_images.image_type = ? and (added_by = ? or (added_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ') and products.for_shop_id IN (' . DB::raw("SELECT `shop_id` FROM `users` WHERE `user_id` = '$supplierId'") . ')))', 'bindParams' => [0, 0, $supplierId]];//todo added_by where clause
                $selectedColumn = ['products.*', 'users.username', 'users.role', 'shops.shop_name', 'product_categories.category_name', 'product_images.image_url'];
                $getAllProducts = $objModelProducts->getAllProducts($where, $selectedColumn);
                $productInfo = json_decode(json_encode($getAllProducts), true);
                $products = new Collection();
                foreach ($productInfo as $key => $val) {
                    $products->push([
                        'checkbox' => '<input type="checkbox" name="id[]" value="' . $val['product_id'] . '">',
                        'product_id' => $val['product_id'],
                        'product_images' => '<img src="' . $val['image_url'] . '" width="30px">',
                        'added_date' => date('d-F-Y', ($val['added_date'])),
                        'shop_name' => $val['shop_name'],
                        'product_name' => $val['product_name'],
                        'price_total' => $val['price_total'],
                        'list_price' => $val['list_price'],
                        'min_qty' => $val['min_qty'],
                        'max_qty' => $val['max_qty'],
                        'category_name' => $val['category_name'],
                        'in_stock' => $val['in_stock'],
                        'username' => $val['username'],
                        'available_countries' => $val['available_countries'],
                        'product_status' => $val['product_status'],
                    ]);
                }

                // FILTERING STARTS FROM HERE //
                $filteringRules = '';
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter' && $_REQUEST['action'][0] != 'filter_cancel') {

                    if ($_REQUEST['product_id'] != '') {
                        $filteringRules[] = "(`products`.`product_id` = " . $_REQUEST['product_id'] . " )";
                    }
                    if ($_REQUEST['date_from'] != '' && $_REQUEST['date_to'] != '') {
                        $filteringRules[] = "(`products`.`added_date` BETWEEN " . strtotime(str_replace('-', ' ', $_REQUEST['date_from'])) . " AND " . strtotime(str_replace('-', ' ', $_REQUEST['date_to'])) . "  )";
                    }
                    if ($_REQUEST['store_name'] != '') {
                        $filteringRules[] = "(`shops`.`shop_name` LIKE '%" . $_REQUEST['store_name'] . "%' )";
                    }
                    if ($_REQUEST['product_name'] != '') {
                        $filteringRules[] = "(`products`.`product_name` LIKE '%" . $_REQUEST['product_name'] . "%' )";
                    }
                    if ($_REQUEST['price_from'] != '' && $_REQUEST['price_to'] != '') {
                        $filteringRules[] = "(`products`.`price_total` BETWEEN " . intval($_REQUEST['price_from']) . " AND " . intval($_REQUEST['price_to']) . "  )";
                    }
                    if ($_REQUEST['list_price_from'] != '' && $_REQUEST['list_price_to'] != '') {
                        $filteringRules[] = "(`products`.`list_price` BETWEEN " . intval($_REQUEST['list_price_from']) . " AND " . intval($_REQUEST['list_price_to']) . "  )";
                    }
                    if ($_REQUEST['minimum_quantity'] != '') {
                        $filteringRules[] = "( `products`.`min_qty` = " . intval($_REQUEST['minimum_quantity']) . ")";
                    }
                    if ($_REQUEST['maximum_quantity'] != '') {
                        $filteringRules[] = "(`products`.`max_qty` = " . intval($_REQUEST['maximum_quantity']) . ")";
                    }
                    if ($_REQUEST['product_categories'] != '') {
                        $filteringRules[] = "(`products`.`category_id` = " . $_REQUEST['product_categories'] . " )";
                    }
                    if ($_REQUEST['added_by'] != '') {
                        $filteringRules[] = "(`users`.`username` LIKE '%" . $_REQUEST['added_by'] . "%' )";
                    }
                    if ($_REQUEST['product_status'] != '') {
                        $filteringRules[] = "(`products`.`product_status` = " . $_REQUEST['product_status'] . " )";
                    }
                    // Filter Implode //
                    $implodedWhere = '';
                    if (!empty($filteringRules)) {
                        $implodedWhere = implode(' AND ', array_map(function ($filterValues) {
                            return $filterValues;
                        }, $filteringRules));
                    }
                    if (!empty($implodedWhere)) {
                        $where = ['rawQuery' => 'products.product_type = ? AND products.product_status IN (0,1,2,3,4) AND product_images.image_type = ? and (added_by = ? or (added_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ') and products.for_shop_id IN (' . DB::raw("SELECT `shop_id` FROM `users` WHERE `user_id` = '$supplierId'") . ')))', 'bindParams' => [0, 0, $supplierId]];
                        $selectedColumn = ['products.*', 'users.username', 'users.role', 'shops.shop_name', 'product_categories.category_name', 'product_images.image_url'];
                        $getAllFilterProducts = $objModelProducts->getAllFilterProducts($where, $implodedWhere, $sortingOrder, $iDisplayLength, $iDisplayStart, $selectedColumn);

                        $productFilter = json_decode(json_encode($getAllFilterProducts), true);
                        $products = new Collection();
                        foreach ($productFilter as $key => $val) {
                            $products->push([
                                'checkbox' => '<input type="checkbox" name="id[]" value="' . $val['product_id'] . '">',
                                'product_id' => $val['product_id'],
                                'product_images' => '<img src="' . $val['image_url'] . '" width="80px">',
                                'added_date' => date('d-F-Y', ($val['added_date'])),
                                'shop_name' => $val['shop_name'],
                                'product_name' => $val['product_name'],
                                'price_total' => $val['price_total'],
                                'list_price' => $val['list_price'],
                                'min_qty' => $val['min_qty'],
                                'max_qty' => $val['max_qty'],
                                'category_name' => $val['category_name'],
                                'in_stock' => $val['in_stock'],
                                'username' => $val['username'],
                                'available_countries' => $val['available_countries'],
                                'product_status' => $val['product_status'],
                            ]);
                        }
                    }
                }
                $status_list = array(
                    0 => array("warning" => "Pending"),
                    1 => array("success" => "Success"),
                    2 => array("primary" => "InActive"),
                    3 => array("warning" => "Rejected"),
                    4 => array("danger" => "Deleted"),
                    5 => array("danger" => "Finished"),
                );
                return Datatables::of($products, $status_list)
                    ->addColumn('action', function ($products) {
                        $action = '<div role="group" class="btn-group "> <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"> <i class="fa fa-cog"></i>&nbsp; <span class="caret"></span></button>';
                        $action .= '<ul role="menu" class="dropdown-menu">';
                        $action .= '<li><a href="javascript:void(0);" class="delete-product" data-cid="' . $products['product_id'] . '"><i class="fa fa-trash"></i>&nbsp;Delete</a></li>';
                        $action .= '</ul>';
                        $action .= '</div>';
                        return $action;
                    })
                    ->addColumn('product_status', function ($products) use ($status_list) {
                        return '<span class="label label-sm label-' . (key($status_list[$products['product_status']])) . '">' . (current($status_list[$products['product_status']])) . '</span>';

                    })
                    ->make();
                // NORMAL DATATABLE CODE END'S HERE//
                break;
        }

    }


    public function deletedProducts(Request $request)
    {
        $objCategoryModel = ProductCategory::getInstance();
        $userId = Session::get('fs_supplier')['id'];

        $where = ['rawQuery' => 'created_by = ? or created_by IN(' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ')', 'bindParams' => [$userId]];
        $allactivecategories = $objCategoryModel->getAllCategoriesWhere($where);

        return view('Supplier/Views/product/deletedProducts', ['allCategories' => $allactivecategories]);

    }


    public function pendingProducts(Request $request)
    {

        $objCategoryModel = ProductCategory::getInstance();
        $userId = Session::get('fs_supplier')['id'];
        $where = ['rawQuery' => 'created_by = ? or created_by IN(' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ')', 'bindParams' => [$userId]];
        $allactivecategories = $objCategoryModel->getAllCategoriesWhere($where);

        return view('Supplier/Views/product/pendingProducts', ['allCategories' => $allactivecategories]);

    }

    public function rejectedProducts(Request $request)
    {

        $objCategoryModel = ProductCategory::getInstance();

        $userId = Session::get('fs_supplier')['id'];
        $where = ['rawQuery' => 'created_by = ? or created_by IN(' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ')', 'bindParams' => [$userId]];

        $allactivecategories = $objCategoryModel->getAllCategoriesWhere($where);
        return view('Supplier/Views/product/rejectedProducts', ['allCategories' => $allactivecategories]);
    }

    public function productAjaxHandler(Request $request)
    {
        $response = array();
        $inputData = $request->input();
        $method = $inputData['method'];
        $response['code'] = 400;
        $response['data'] = array();
        $response['message'] = "Invalid request.";
        $objModelProducts = Products::getInstance();
        $objModelProductCategories = ProductCategory::getInstance();
        $userId = Session::get('fs_supplier')['id'];
        if ($method) {
            switch ($method) {
                case 'getOptionVariantsWhere'://todo
                    $response['code'] = 400;
                    $response['data'] = array();
                    $response['message'] = "Please select an option to add";
                    if (isset($inputData['optionId']) && $inputData['optionId'] != '') {
                        $optionId = (int)$inputData['optionId'];
                        $objModelProductOption = ProductOption::getInstance();
                        $whereForOption = ['rawQuery' => 'option_id = ? and (added_by = ? or added_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . '))', 'bindParams' => [$optionId, $userId]];
                        $optionDetails = $objModelProductOption->getOptionWhere($whereForOption);
                        $objModelProductOptionVariants = ProductOptionVariant::getInstance();
                        $whereForOptionVariant = ['rawQuery' => 'option_id = ? and (added_by = ? or added_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . '))', 'bindParams' => [$optionId, $userId]];
                        $allOptionVariants = $objModelProductOptionVariants->getAllVariantsWhere($whereForOptionVariant);
                        $response['code'] = 200;
                        $response['message'] = 'Option data';
                        $response['data']['optionDetails'] = $optionDetails;
                        $response['data']['optionVariants'] = $allOptionVariants;
                    }
                    break;

                case "getFeaturesWhereCatIdLike"://todo
                    $response['code'] = 400;
                    $response['data'] = array();
                    $response['message'] = "";
                    if (isset($inputData['catid']) && $inputData['catid'] != '') {
                        $catId = (int)$inputData['catid'];
                        $catFlag = true;
                        $parentCategory = array();
                        $count = 1;
                        $bindParamsForFeature = array();
                        $queryForFeature = "";
                        $queryForFeatureGroup = "";
                        while ($catFlag) {
                            if ($count == 1) {
                                $queryForFeatureGroup = '(product_features.group_flag = 1) and (product_features.added_by = ? or product_features.added_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ')) and (product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? ';
                                $queryForFeature = '(group_flag = 0 and parent_id = 0) and (product_features.added_by = ? or product_features.added_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ')) and (for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? ';
                                array_push($bindParamsForFeature, $userId);

                            } else {
                                $catId = $parentCategory['data']['parent_category_id'];
                                $queryForFeatureGroup .= ' OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? ';
                                $queryForFeature .= ' OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? ';
                            }
                            array_push($bindParamsForFeature, "%,$catId");
                            array_push($bindParamsForFeature, "%,$catId,%");
                            array_push($bindParamsForFeature, "$catId,%");
                            array_push($bindParamsForFeature, "$catId");
                            $count++;
                            $parentCategory = array();
                            $whereForCat = ['rawQuery' => 'category_id = ? and category_status = 1 and (created_by = ? or created_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . '))', "bindParams" => [$catId, $userId]];
                            $parentCategory = json_decode($objModelProductCategories->getCategoryWhere($whereForCat), true);
                            if ($parentCategory['data'] == null) {
                                $catFlag = false;
                            }
                        }
                        $queryForFeature .= ")";
                        $queryForFeatureGroup .= ")";
                        $objModelProductFeature = ProductFeatures::getInstance();
                        $whereForFeature = ['rawQuery' => $queryForFeature, 'bindParams' => $bindParamsForFeature];
//                        $featureDetails = json_decode($objModelProductFeature->getAllFeaturesWhere($whereForFeature), true);
                        $featureDetails = json_decode($objModelProductFeature->getAllFeaturesWithVariantsWhere($whereForFeature), true);

                        $whereForFeatureGroup = ['rawQuery' => $queryForFeatureGroup, 'bindParams' => $bindParamsForFeature];
                        $featureGroups = json_decode($objModelProductFeature->getAllFGsWithFsWhere($whereForFeatureGroup), true);
//                        dd($featureGroups);
                        foreach ($featureGroups['data'] as $keyFG => $valueFG) {
                            $whereForFs = ['rawQuery' => "product_features.parent_id IN (?) and (added_by = ? or added_by IN (" . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . "))", "bindParams" => [$valueFG['feature_ids'], $userId]];
                            $featureGroups['data'][$keyFG]['featureDetails'] = json_decode($objModelProductFeature->getAllFeaturesWithVariantsWhere($whereForFs), true)['data'];
                        }
//                        dd($featureGroups);
//                        dd($featureDetails);
                        $response['code'] = $featureDetails['code'];
                        $response['message'] = $featureDetails['message'];
                        $response['data']['featureDetails'] = $featureDetails['data'];
                        $response['data']['featureGroupDetails'] = $featureGroups['data'];
//                        dd($response);
                    }
                    break;

                case "deleteProductImage":
                    $objModelProductImage = ProductImage::getInstance();
                    $imageId = $inputData['imageId'];
                    $productId = $inputData['productId'];
                    $whereForProdImgDel = ['rawQuery' => "for_product_id = ? and pi_id = ? and products.added_by = ?", 'bindParams' => [$productId, $imageId, $userId]];
                    $selColsForProdImgDel = ['*'];
                    $resultProdImgDel = json_decode($objModelProductImage->getImageWhere($whereForProdImgDel, $selColsForProdImgDel), true);
                    if ($resultProdImgDel['code'] == 200) {
                        if (!strpos($resultProdImgDel['data']['image_url'], 'placeholder') && $resultProdImgDel['data']['image_upload_type'] == 0) {
                            File::delete(storage_path() . $resultProdImgDel['data']['image_url']);
                        }
                        $resultProdImgDel = json_decode($objModelProductImage->deleteImagesWhere($whereForProdImgDel), true);
                    }
                    $response = $resultProdImgDel;
                    break;

                default:
                    break;
            }
        }
        echo json_encode($response, true);
        die;
    }


    public function addProductCSV(Request $request)
    {
        $rawquery = 'category_name = ? and parent_category_id = ? and (created_by IN (' . DB::raw("SELECT `id` FROM `users` WHERE `role` IN(4,5)") . ') or created_by = ?)';

//TEST START [TRY THIS BY INSERTING EVAL INTO DATABASE AND FETCH AND ECHO]
//        var_dump("eval(die(\"Asd\"));");
//        if (("eval(die(\"Asd\"));"))
//            die("a");
//        else
//            die("Ad");
//TEST END

        if ($request->isMethod('post')) {

//            return Redirect::back()->with(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);

//            echo "<pre>";
//            print_r($_FILES);
//            die;
//            dd($request->all());
            // Import a user provided file
            $destinationFolder = 'uploads/';
            if (Input::hasFile('productcsv')) {
                $destinationFolder .= "csv/products" . '/';
                $destinationPath = storage_path($destinationFolder);

                $fileCSV = Input::file('productcsv')->move($destinationPath, "productcsvsamplesupplier.csv");
//                $fileCSV = $destinationPath . "productcsvsamplesupplier.csv";
//                echo $fileCSV;
                $results = array();
                Excel::load($fileCSV, function ($reader) use ($results) {
                    $userId = Session::get('fs_supplier')['id'];

                    //Model instances start
                    $objModelProducts = Products::getInstance();
                    $objModelProductMeta = ProductMeta::getInstance();
                    $objModelCategory = ProductCategory::getInstance();
                    $objModelFeatures = ProductFeatures::getInstance();
                    $objModelShops = Shops::getInstance();
                    $objModelProductFeature = ProductFeatures::getInstance();
                    $objModelProductImage = ProductImage::getInstance();

                    $objModelProductOption = ProductOption::getInstance();
                    $objModelProductOptionVariant = ProductOptionVariant::getInstance();
                    $objModelProdOptVarComb = ProductOptionVariantsCombination::getInstance();
                    $objModelProdOptVarRel = ProductOptionVariantRelation::getInstance();

                    //Model instances end

                    $results = $reader->all()->toArray();
//                    $reader->each(function ($row) {
//                    $tempRow = $row->toArray();
                    $allErrors = array();
                    foreach ($results as $keyRow => $valueRow) {
//                        echo "<br>";//reference comment
//                        echo $keyRow;//reference comment
                        $allErrors[$keyRow] = array('status' => 'success');
                        DB::beginTransaction();
//                        dd(DB::transactionLevel());//todo IMPORTANT FOR FUTURE WORK
//                        print_r($valueRow);
//                    }
//                    die;
//                    foreach ($results as $keyRow => $valueRow) {
                        $tempRow = $valueRow;
                        $rules = array(
                            'shop' => 'exists:shops,shop_name',
                            'name' => 'required',
                            'main_image_url' => 'required',
                            'category' => 'required'
                        );
                        $messages = array(
                            'shop.exist' => 'No such shop exists.',
                            'name.required' => 'Invalid product name.',
                            'main_image_url.required' => 'Invalid image provided.',
                            'category.required' => 'No such category exists.',
                        );
                        $validator = Validator::make($tempRow, $rules, $messages);
                        if ($validator->fails()) {
                            $allErrors[$keyRow] = array('status' => 'error', 'validator' => $validator->errors()->all(), 'message' => "Product #$keyRow " . (isset($tempRow['name']) && $tempRow['name'] != '' ? " : <b>" . $tempRow['name'] . "</b>" : '') . ' was not uploaded. Please correct the following errors.');
//                            dd($validator);
//                            die("Asdasd");
//                            return Redirect::back()
//                                ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
//                                ->withErrors($validator)
//                                ->withInput();
                        } else {
//                            dd("Asd");
                            //Insert to database here

                            $allErrors[$keyRow]['manual'] = array();
                            //SHOP VALIDATION START
                            $whereForShop = ['rawQuery' => 'shop_name = ?', 'bindParams' => [$tempRow['shop']]];
                            $shopDetails = json_decode($objModelShops->getShopWhere($whereForShop), true);
//                        dd($shopDetails);
                            if ($shopDetails['code'] != 200) {
                                $allErrors[$keyRow]['status'] = "error";
                                $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . '</b> was not uploaded. Please correct the following errors.';
                                $allErrors[$keyRow]['manual']['shop'] = array($shopDetails['message']);
                                DB::rollBack();
                                goto blockRollBack;
                            }
                            //SHOP VALIDATION END

                            //CATEGORY VALIDATION START
                            $categories = explode("::", $tempRow['category']);

                            $parentCatId = 0;
                            /*$catFlag = true;
                            $parentCategory = array();
                            $count = 1;
                            $bindParamsForFeature = array();
                            $queryForFeature = "";
                            $queryForFeatureGroup = "";
                            */

                            $selectedCategoryDetails = array();
                            foreach ($categories as $keyCat => $valueCat) {
                                $rawquery = "category_name = ? and parent_category_id = ? and (created_by = ? or created_by = ?)";
                                $bindParams = [$valueCat, $parentCatId, $userId, $shopDetails['data']['user_id']];
                                $whereForCategory = ['rawQuery' => $rawquery, 'bindParams' => $bindParams];
                                $categoryDetails = json_decode($objModelCategory->getCategoryWhere($whereForCategory), true);
                                if ($categoryDetails['code'] == 200) {
                                    $parentCatId = $categoryDetails['data']['category_id'];
                                    if ($keyCat == count($categories) - 1) {
                                        $selectedCategoryDetails = $categoryDetails['data'];
                                    }
                                    /*
                                        $catId = $categoryDetails['data']['category_id'];
                                        if ($queryForFeatureGroup == "" && $queryForFeature == "") {
                                            $queryForFeatureGroup = '(product_features.group_flag = 1) and (product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ?';
                                            $queryForFeature = '(group_flag = 0 and parent_id = 0) and (for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ?';
                                        } else {
                                            $queryForFeatureGroup .= 'OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ?';
                                            $queryForFeature .= 'OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ?';
                                        }
                                        array_push($bindParamsForFeature, "%,$catId");
                                        array_push($bindParamsForFeature, "%,$catId,%");
                                        array_push($bindParamsForFeature, "$catId,%");
                                        array_push($bindParamsForFeature, "$catId");
                                        */
                                } else {
                                    $allErrors[$keyRow]['status'] = "error";
                                    $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . '</b> was not uploaded. Please correct the following errors.';
                                    $allErrors[$keyRow]['manual']['category'] = array("No such category was found.");
                                    DB::rollBack();
                                    goto blockRollBack;
                                }
                            }
//                        dd($categoryDetails);
                            if (empty($selectedCategoryDetails)) {
                                $allErrors[$keyRow]['status'] = "error";
                                $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . '</b> was not uploaded. Please correct the following errors.';
                                $allErrors[$keyRow]['manual']['category'] = array("No such category was found.");
                                DB::rollBack();
                                goto blockRollBack;
                            }
//CATEGORY VALIDATION END

//FEATURES AND FEATURE GROUP VALIDATION START
                            /*
                             if ($queryForFeature != "" && $queryForFeatureGroup != "") {
                                $queryForFeature .= ")";
                                $queryForFeatureGroup .= ")";
                                $whereForFeature = ['rawQuery' => $queryForFeature, 'bindParams' => $bindParamsForFeature];
                                $featureDetails = json_decode($objModelProductFeature->getAllFeaturesWithVariantsWhere($whereForFeature), true);

                                $whereForFeatureGroup = ['rawQuery' => $queryForFeatureGroup, 'bindParams' => $bindParamsForFeature];
                                $featureGroups = json_decode($objModelProductFeature->getAllFGsWithFsWhere($whereForFeatureGroup), true);
                                foreach ($featureGroups['data'] as $keyFG => $valueFG) {
                                    $whereForFs = ['rawQuery' => "product_features.parent_id IN (?)", "bindParams" => [$valueFG['feature_ids']]];
                                    $featureGroups['data'][$keyFG]['featureDetails'] = json_decode($objModelProductFeature->getAllFeaturesWithVariantsWhere($whereForFs), true)['data'];
                                }
                            }
                            //                        $whereForFeatures = ['rawQuery' => 'for_categories LIKE ? or for_categories LIKE ? or for_categories LIKE ? or for_categories LIKE ?', 'bindParams' => ["%$parentCatId%", "%$parentCatId", "$parentCatId%", "$parentCatId"]];
                            //                        $featureDetails = $objModelFeatures->getAllFeaturesWhere($whereForFeatures);
                            //                        dd($featureDetails);
                            */
//FEATURES AND FEATURE GROUP VALIDATION END


//Product general info start

                            $errors = array();
                            $productData = array();
                            $productData['product_name'] = trim($tempRow['name']);
                            $productData['for_shop_id'] = $shopDetails['data']['shop_id'];
                            $productData['product_type'] = 0;//$tempRow['Type'];
//                        $productData['min_qty'] = $tempRow['minimum_order_quantity'];
//                        $productData['max_qty'] = $tempRow['maximum_order_quantity'];
                            $productData['category_id'] = $selectedCategoryDetails['category_id'];//$tempRow['category_id'];
//                        $productData['for_gender'] = $tempRow['for_gender'];
                            $productData['price_total'] = $tempRow['price'];
//                        $productData['list_price'] = $tempRow['list_price'];
                            $productData['in_stock'] = $tempRow['quantity'];
                            $productData['added_date'] = time();
                            $productData['added_by'] = $userId;
                            $productData['status_set_by'] = $userId;

                            $resultProdInsert = json_decode($objModelProducts->addNewProduct($productData), true);
                            if ($resultProdInsert['code'] == 200) {
                                $insertedProductId = $resultProdInsert['data'];
                                $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded successfully.";
//                        dd($insertedProductId);

//Product general info end

                                //Product meta data start
                                $productMetaData['product_id'] = $insertedProductId;//todo check isset and set value using ternary operator
                                $productMetaData['full_description'] = trim($tempRow['full_description']);
                                $productMetaData['short_description'] = trim($tempRow['short_description']);
                                $productMetaData['weight'] = $tempRow['weight'];
                                $productMetaData['shipping_freight'] = $tempRow['shipping_freight'];
//                            $productMetaData['shipping_params'] = json_encode($shippingParams);
//                            $productMetaData['quantity_discount'] = json_encode($inputData['quantity_discount']);
//                            $productMetaData['product_tabs'] = json_encode($inputData['product_tabs']);

                                $insertedProductMetaId = $objModelProductMeta->addProductMetaData($productMetaData);
                                if (!$insertedProductMetaId) {
                                    $allErrors[$keyRow]['status'] = "warning";
                                    $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                    $allErrors[$keyRow]['manual']['metadata'] = array("Some of the minor product details could not be saved.");
//                                    goto blockRollBack;
                                }

                                //Product meta data end

//Product features start
//                        $objModelProductFeature->addFeature($featuresData);
//Product features end

//Product images start

                                $mainImage = file_get_contents($tempRow['main_image_url']);
                                $imageData = array();
                                if ($mainImage == 0) {
                                    $mainImageURL = uploadImageToStoragePath($tempRow['main_image_url'], 'product_' . $insertedProductId, 'product_' . $insertedProductId . '_0_' . time() . '.jpg', 724, 1024);
                                    if ($mainImageURL) {
                                        $mainImageData['for_product_id'] = $insertedProductId;
                                        $mainImageData['image_type'] = 0;
                                        $mainImageData['image_upload_type'] = 0;
                                        $mainImageData['image_url'] = $mainImageURL;
                                        $imageData[] = $mainImageData;
                                    }
                                } else {
                                    $allErrors[$keyRow]['status'] = "warning";
                                    $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                    $allErrors[$keyRow]['manual']['mainimage'] = array("Main image could not be uploaded, You can upload it on edit section.");
                                }

                                if ($tempRow['extra_image_urls'] != "") {
                                    $otherImages = explode(",", $tempRow['extra_image_urls']);
                                    foreach ($otherImages as $otherImageKey => $otherImage) {
                                        if ($otherImage != '') {
                                            $otherImageURL = uploadImageToStoragePath($otherImage, 'product_' . $insertedProductId, 'product_' . $insertedProductId . '_' . ($otherImageKey + 1) . '_' . time() . '.jpg', 724, 1024);
                                            if ($otherImageURL) {
                                                $otherImageData['for_product_id'] = $insertedProductId;
                                                $otherImageData['image_type'] = 1;
                                                $otherImageData['image_upload_type'] = 0;
                                                $otherImageData['image_url'] = $otherImageURL;
                                                $imageData[] = $otherImageData;
                                            }
                                        }
                                    }
                                }
                                if (!empty($imageData)) {
                                    $objModelProductImage->addNewImage($imageData);
                                }

//Product images end


//Product shipping info start

//Product shipping info end


//Product options start

//                            $tempRow['options'] = "Size=Small|Color=Red::100____Size=Small|Color=Blue::200____Size=XL|Color=Red::100";
                                DB::beginTransaction();
                                if ($tempRow['options'] != '') {
                                    $allErrors[$keyRow]['manual']['options'] = array();
                                    $reqDataOVCs = explode("____", $tempRow['options']);
                                    $countOVC = count($reqDataOVCs);
                                    $optionsCount = 0;
                                    $dataOptionsVar = array();
                                    $dataOptionsVarIds = array();
                                    $whereForOptionVarRQ = '';
                                    $whereForOptionVarBP = array();

                                    $whereForOptVarRQ = '';
                                    $whereForOptVarBP = array();
                                    $whereForJoinForOptVarRQ = '';
                                    $whereForJoinForOptVarBP = array();

                                    $ovcData = array();//will contain data from csv in rearranged array format
                                    $errFlagOVC = false;
                                    foreach ($reqDataOVCs as $keyRDOVC => $valueRDOVC) {
                                        $reqDataOVCOptVars = explode("::", $valueRDOVC);//get all vars of a combination
                                        //todo need to code if there are no combinations but only one option chosen for the product
                                        $reqDataOVCVars = explode("|", $reqDataOVCOptVars[0]);//split all vars of a combination
                                        if ($keyRDOVC == 0) {
                                            $reqDataOptCount = count($reqDataOVCVars);
                                        }
                                        if (count($reqDataOVCVars) != $reqDataOptCount) {//see if options count is same in all combinations
                                            $errFlagOVC = true;
                                            $allErrors[$keyRow]['status'] = "warning";
                                            $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                            array_push($allErrors[$keyRow]['manual']['options'], "You need to include all options in all combinations.");
                                            break;
                                        } else {
                                            foreach ($reqDataOVCVars as $keyOVCVar => $valueOVCVar) {//to generate query to get opt vars from db
                                                $tmpOvdata = explode("=", $valueOVCVar);

//                                        if ($keyOID != 0 || $keyOvd != 0) {
//                                            $whereForOptionVarRQ .= ' or ';
//                                        }
//                                        $whereForOptionVarRQ .= '(product_options.option_name = ? and product_options.option_id = ov.option_id and product_options.status = 1 and ov.status = 1 and ov.variant_name = ?)';
//                                        array_push($whereForOptionVarBP, $tmpOvdata [0]);
//                                        array_push($whereForOptionVarBP, $tmpOvdata [1]);

                                                if ($keyRDOVC != 0 || $keyOVCVar != 0) {
                                                    $whereForOptVarRQ .= ' or ';
                                                    $whereForJoinForOptVarRQ .= ' or ';
                                                }
                                                $whereForOptVarRQ .= '(po.option_name = ? and po.status = 1 and pov.status = 1)';
                                                $whereForJoinForOptVarRQ .= '(pov.status = 1 and pov.variant_name = ?)';
                                                array_push($whereForOptVarBP, $tmpOvdata[0]);
                                                array_push($whereForJoinForOptVarBP, $tmpOvdata[1]);
                                            }
                                        }
                                        $ovcData[$keyRDOVC]['quantity'] = $reqDataOVCOptVars[1];
                                        $ovcData[$keyRDOVC]['combination'] = $reqDataOVCVars;
                                    }
                                    //            dd($ovcData);
                                    if (!$errFlagOVC) {

                                        $whereForOptVar = ['rawQuery' => $whereForOptVarRQ, 'bindParams' => $whereForOptVarBP];//product_id = ?", 'bindParams' => [$productId]];
                                        $whereForJoinForOptVar = ['rawQuery' => $whereForJoinForOptVarRQ, 'bindParams' => $whereForJoinForOptVarBP];
                                        $dataOptWithVars = json_decode($objModelProductOptionVariant->getOptionsWithVarsWhere($whereForOptVar), true);//, ['*'], $whereForJoinForOptVar
                                        if ($dataOptWithVars['code'] == 200) {
                                            if (count($dataOptWithVars['data']) == (count($whereForOptVarBP) / count($reqDataOVCs))) {

                                                //BLOCK TO COMPARE COMBINATIONS WITH INPUT COMBINATIONS START

//                                        $whereForOptionVar = ['rawQuery' => $whereForOptionVarRQ, 'bindParams' => $whereForOptionVarBP];
//                                        $optionVarResult = json_decode($objModelProductOptionVariant->getOptionVariantWhere($whereForOptionVar), true);
//                                        dd($optionVarResult);

//                                                echo "<pre>";//reference comment
                                                $dataForOVRInsert = array();
                                                $dataCsvCombs = array();
                                                foreach ($ovcData as $keyovcData => $valueOvcData) {//parse combinations one by one
                                                    //$inputCombinationVars = explode("|", $valueOvcData['combination']);//get all vars of a combination
                                                    $tempRes = array();
                                                    $tempvar = array();
                                                    //compare and get data of all vars from db vars result
                                                    $tempCombId = array();
                                                    foreach ($valueOvcData['combination'] as $keyICV => $valueICV) {
                                                        $tempvar = explode("=", $valueICV);
                                                        $tempOVFoundFlag = false;
                                                        $optionId = 0;
                                                        $searchOptionKey = null;
                                                        array_where($dataOptWithVars['data'], function ($key, $value) use ($tempvar, &$tempRes, $objModelProductOptionVariant, $userId, &$tempOVFoundFlag, &$optionId, &$searchOptionKey, &$tempCombId, &$dataForOVRInsert) {
                                                            $tempAllVariantData = json_decode($value['all_variant_data'], true);
                                                            $searchResKey = array_search($tempvar[1], array_column($tempAllVariantData, 'VN'));
                                                            if ($tempvar[0] == $value['option_name']) {
                                                                $optionId = $value['option_id'];
                                                                if (!array_key_exists($optionId, $dataForOVRInsert)) {
                                                                    $dataForOVRInsert[$optionId] = array('variant_data' => array(), 'option_id' => $optionId);
                                                                }
                                                                $optionName = $value['option_name'];
                                                                $searchOptionKey = $key;
                                                                if (is_int($searchResKey)) {
//                                                                    echo $tempvar[1] . " --- found in db ";//reference comment
                                                                    if (array_search($tempAllVariantData[$searchResKey]['VID'], array_column($dataForOVRInsert[$optionId]['variant_data'], 'VID')) === false) {
//                                                                        echo "--- var not found in toinsertOVRarray";//reference comment
                                                                        array_push($dataForOVRInsert[$optionId]['variant_data'], $tempAllVariantData[$searchResKey]);
                                                                    }
                                                                    array_push($tempRes, $tempAllVariantData[$searchResKey]);
                                                                    array_push($tempCombId, $tempAllVariantData[$searchResKey]['VID']);
                                                                    $tempOVFoundFlag = true;
                                                                }
                                                            }
                                                        });
                                                        if ($optionId > 0) {
                                                            if (!$tempOVFoundFlag) {
//                                                                echo "not found " . $tempvar[1];//reference comment
                                                                $dataForOV = ['option_id' => $optionId, 'variant_name' => $tempvar[1], 'added_by' => $userId];
                                                                $resultPOVInserted = json_decode($objModelProductOptionVariant->addVariantAndGetID($dataForOV), true);
                                                                if ($resultPOVInserted['code'] == 200) {
//                                                                    echo "inserted";//reference comment
                                                                    $tempDataForGenCombs = ["VID" => $resultPOVInserted['data'], "VN" => $tempvar[1], "PM" => "0.000", "PMT" => "1", "WM" => "0.000", "WMT" => "1", "STTS" => "1"];
                                                                    $tempAllVariantData = json_decode($dataOptWithVars['data'][$searchOptionKey]['all_variant_data']);
                                                                    array_push($tempAllVariantData, $tempDataForGenCombs);
                                                                    $dataOptWithVars['data'][$searchOptionKey]['all_variant_data'] = json_encode($tempAllVariantData);
                                                                    array_push($dataForOVRInsert[$optionId], $tempDataForGenCombs);
                                                                    array_push($tempRes, $tempDataForGenCombs);
                                                                    array_push($tempCombId, $tempDataForGenCombs['VID']);
                                                                } else {
//                                                                    echo "not inserted";//reference comment
                                                                    $allErrors[$keyRow]['status'] = "warning";
                                                                    $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                                                    array_push($allErrors[$keyRow]['manual']['options'], 'Variant ' . $tempvar[1] . ' could not be added. Combinations were affected and not inserted.');
                                                                    DB::rollBack();
                                                                    goto blockRollBack2;
                                                                }
                                                            }
                                                        } else {
                                                            $allErrors[$keyRow]['status'] = "warning";
                                                            $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                                            array_push($allErrors[$keyRow]['manual']['options'], "$valueOvcData : invalid combination, not added.");
                                                        }
//                                                        echo "<br>";//reference comment
                                                    }
                                                    $tempRes['variantIds'] = implode(",", array_column($tempRes, 'VID'));
                                                    $tempRes['variantNames'] = implode(",", array_column($tempRes, 'VN'));
                                                    $tempRes['combinationId'] = $tempCombId;
                                                    sort($tempCombId, SORT_NUMERIC);
                                                    $tempRes['combinationIdTmp'] = implode("_", $tempCombId);
                                                    //$tempRes['optionName'] = $optionName;
                                                    //$tempRes['optionId'] = $optionId;
                                                    array_push($dataCsvCombs, $tempRes);
                                                    //print_r($tempRes);
                                                    //if actual generated combinations' contain same amount as vars in the combination
//                                                    if (count($tempRes) != count($tempvar)) {//if all variants found
//                                                        array_push($allErrors[$keyRow]['manual']['options'], "$valueOvcData : invalid combination, not added.");
//                                                    }
                                                }

//                                            dd($dataForOVRInsert);//todo write in csv blade "Adding variants, if they are global their price modifier
//                                            dd($dataCsvCombs);
                                                array_where($dataForOVRInsert, function ($key, $value) use ($insertedProductId, &$dataForOVRInsert) {
                                                    $dataForOVRInsert[$key]['variant_data'] = json_encode($value['variant_data'], true);
                                                    $dataForOVRInsert[$key]['status'] = 1;
                                                    $dataForOVRInsert[$key]['product_id'] = $insertedProductId;
                                                });
                                                $resultInsOVRs = json_decode($objModelProdOptVarRel->insertOptVarRels($dataForOVRInsert), true);
                                                if ($resultInsOVRs['code'] != 200) {
                                                    $allErrors[$keyRow]['status'] = "warning";
                                                    $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                                    array_push($allErrors[$keyRow]['manual']['options'], $resultInsOVRs['message']);
                                                    DB::rollBack();
                                                    goto blockRollBack2;
                                                }
                                                //                        dd($dataForOVRInsert);
                                                $tempcombs = $this->generateCombinations($dataOptWithVars['data'], 0, '', array());//generate all combinations using db data
                                                //                        dd($tempcombs);
                                                if (count($ovcData) <= count($tempcombs)) {

                                                    $includedCombs = array_intersect(array_column($tempcombs, 'combinationIdTmp'), array_column($dataCsvCombs, 'combinationIdTmp'));

                                                    $dataForOVCInsert = array();
                                                    foreach ($tempcombs as $keyTC => $valueTC) {
                                                        $tempDataForOVCI['product_id'] = $insertedProductId;
                                                        $tempDataForOVCI['variant_ids'] = $valueTC['combinationId'];
                                                        $tempDataForOVCI['quantity'] = 0;//todo format for price and quantity in csv
                                                        $tempDataForOVCI['exception_flag'] = 1;
                                                        if (in_array($keyTC, array_keys($includedCombs))) {
                                                            $tempDataForOVCI['exception_flag'] = 0;
                                                        }
                                                        array_push($dataForOVCInsert, $tempDataForOVCI);
                                                    }

                                                    //                            dd($dataForOVCInsert);
                                                    $resultInsPOVC = json_decode($objModelProdOptVarComb->addNewOptionVariantsCombination($dataForOVCInsert), true);
                                                    if ($resultInsPOVC['code'] != 200) {
                                                        $allErrors[$keyRow]['status'] = "warning";
                                                        $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                                        array_push($allErrors[$keyRow]['manual']['options'], $resultInsPOVC['message']);
                                                        DB::rollBack();
                                                        goto blockRollBack2;
                                                    }

                                                    ///////////////////////////////////////////////WORKING HERE

//                                            foreach ($optionVarResult['data'] as $valOVRes) {
//                                                array_push($dataOptionsVar, $optionVarResult['data']);
//                                                array_push($dataOptionsVarIds[$optionVarResult['data']['option_id']], $optionVarResult['data']['variant_id']);
//                                            }
                                                } else {
                                                    $allErrors[$keyRow]['status'] = "warning";
                                                    $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                                    array_push($allErrors[$keyRow]['manual']['options'], "Invalid combinations exists. Combinations not updated.");
                                                    DB::rollBack();
                                                    goto blockRollBack2;
                                                    //error invalid combinations added
                                                }
                                            } else {
                                                $allErrors[$keyRow]['status'] = "warning";
                                                $allErrors[$keyRow]['message'] = "Product #$keyRow : <b>" . $tempRow['name'] . "</b> was uploaded with some warnings. Please check below warnings and click <a href='/supplier/edit-product/$insertedProductId'>here</a> to update.";
                                                array_push($allErrors[$keyRow]['manual']['options'], "Invalid combinations exists. Combinations not updated.");
                                                DB::rollBack();
                                                goto blockRollBack2;
                                                //error invalid options specified
                                            }
                                        }
                                        //BLOCK TO COMPARE COMBINATIONS WITH INPUT COMBINATIONS END

                                    }
                                    if (empty($allErrors[$keyRow]['manual']['options'])) {
                                        unset($allErrors[$keyRow]['manual']['options']);
                                    }

                                }
                                DB::commit();
                                blockRollBack2:{
                                    //empty block to direct code flow further if any error in combinations
                                }
//                            dd("options end");

//Product options end


//Product tags start

//Product tags end

                                DB::commit();
                            } else {
                                DB::rollBack();
                            }
                            blockRollBack:{
                                //empty block to direct code flow further if any error in combinations
                            }
//                            if (empty($allErrors[$keyRow]['manual'])) {
//                                unset($allErrors[$keyRow]['manual']);
//                            }
                        }
//                        if (empty($allErrors[$keyRow])) {
//                            unset($allErrors[$keyRow]);
//                        }
                    }
                    $forReturnStatus = 'success';
                    $forReturnSAlertClass = 'alert-success';
                    $errorProdCount = array_search("success", array_column($allErrors, 'status'));
                    if ($errorProdCount < count($results)) {
                        $forReturnStatus = 'warning';
                        $forReturnSAlertClass = 'alert-warning';
                        $forReturnMsg = 'Please correct the following errors for some products and try again.';
                    }
                    if ($errorProdCount == count($results)) {
                        $forReturnStatus = 'error';
                        $forReturnSAlertClass = 'alert-danger';
                        $forReturnMsg = 'Please correct the following errors and try again.';
                    }
//                    dd($allErrors);
                    return Redirect::back()
//                    return redirect()->action(
//                        'Supplier\Controllers\ProductController@addProductCSV'
//                    )
//                    return view('Supplier/Views/product/addProductCSV')
                        ->with(["status" => $forReturnStatus, 'msg' => $forReturnMsg, 'data' => $allErrors, 'alert-class' => $forReturnSAlertClass]);
                });

            } else {
                return Redirect::back()
//                return redirect()->action(
//                    'Supplier\Controllers\ProductController@addProductCSV'
//                )
//                return view('Supplier/Views/product/addProductCSV')
                    ->with(["status" => 'error', 'msg' => 'Please upload a file and try again.', 'alert-class' => 'alert-danger']);
            }
        }
        return view('Supplier/Views/product/addProductCSV');
    }

    public function editProduct(Request $request, $productId)
    {
        //todo added_by
        //GET from product            //GET from productmeta
        $userId = Session::get('fs_supplier')['id'];
        $objModelProducts = Products::getInstance();
        $whereForProduct = ['rawQuery' => 'products.product_id = ?', 'bindParams' => [$productId]];
        $productData = json_decode($objModelProducts->getProductWhere($whereForProduct), true);

        if (!empty($productData['data'])) {


            $objModelCategory = ProductCategory::getInstance();
            $objModelFeatures = ProductFeatures::getInstance();
            $objModelProductMeta = ProductMeta::getInstance();
            $objModelProductImage = ProductImage::getInstance();
            $objModelProductOption = ProductOption::getInstance();
            $objModelProductOptionVariant = ProductOptionVariant::getInstance();
            $objModelProductOptVarRel = ProductOptionVariantRelation::getInstance();
            $objModelProductFVR = ProductFeatureVariantRelation::getInstance();
            $objModelProductOptVarCombination = ProductOptionVariantsCombination::getInstance();
            $objModelProductFeature = ProductFeatures::getInstance();
            $objModelShops = Shop::getInstance();


            $returnData = ['code' => 400, "message" => "", "data" => null];
            if ($request->isMethod('post')) {

//                dd($request->all());
                $inputData = $request->input('product_data');//Excludes image
//                $inputData = $request->all()['product_data'];//Includes image

                $returnData['message'] = "Nothing to update.";
//            print_a($inputData['options']);
//            print_a($_FILES);

                if (isset($inputData['updateFormName'])) {
                    $updateFormName = $inputData['updateFormName'];
                    $errors = array();
//                            dd($inputData);

                    switch ($updateFormName) {//to manage requests from a page with multiple forms
                        case "general":
                            $rules = [
                                'product_name' => 'required',//TODO need more validation here for duplicate check
                                'price' => 'required',
                                'in_stock' => 'required',
                                'shop_id' => 'required|integer|min:0',
                                'comment' => 'max:100',
                            ];
                            $messages = array();
                            $validator = Validator::make($inputData, $rules, $messages);
                            if ($validator->fails()) {
                                return Redirect::back()
                                    ->with(["code" => 400, "status" => 'error', 'message' => 'Please correct the following errors.'])
                                    ->withErrors($validator)
                                    ->withInput();
                            } else {

                                $whereForProdUpdate = ['rawQuery' => 'product_id = ?', 'bindParams' => [$productId]];
                                $productDataForUpdate = array();
                                $productDataForUpdate['product_name'] = trim($inputData['product_name']);
                                $productDataForUpdate['for_shop_id'] = $inputData['shop_id'];
                                if (array_key_exists('product_type', $inputData))
                                    $productDataForUpdate['product_type'] = 1;
                                $productDataForUpdate['min_qty'] = $inputData['minimum_order_quantity'];
                                $productDataForUpdate['max_qty'] = $inputData['maximum_order_quantity'];
                                $productDataForUpdate['category_id'] = $inputData['category_id'];
                                $productDataForUpdate['for_gender'] = $inputData['for_gender'];
                                $productDataForUpdate['price_total'] = $inputData['price'];
                                $productDataForUpdate['list_price'] = $inputData['list_price'];
                                $productDataForUpdate['in_stock'] = $inputData['in_stock'];
                                $productDataForUpdate['added_date'] = time();
//                                $productDataForUpdate['added_by'] = $userId;
                                $productDataForUpdate['status_set_by'] = $userId;

                                $resultProdData = json_decode($objModelProducts->updateProductsWhere($productDataForUpdate, $whereForProdUpdate), true);
                                if ($resultProdData['code'] != 200) {
                                    $errors[] = "Product data not updated. Please try again";
                                }

                                //--------------------------PRODUCT-METADATA----------------------------//
                                $productMetaData['full_description'] = trim($inputData['full_description']);
                                $productMetaData['short_description'] = trim($inputData['short_description']);

                                $resultUpdatedProdMetadata = json_decode($objModelProductMeta->updateProdMetadataWhere($productMetaData, $whereForProdUpdate), true);
                                if ($resultUpdatedProdMetadata['code'] != 200 && $resultUpdatedProdMetadata['code'] != 100) {
                                    $errors[] = 'Sorry, some of the product data were not added, please update the same on the edit section.';
                                }
                                //--------------------------END PRODUCT-METADATA----------------------------//

                                //------------------------PRODUCT FEATURES START HERE---------------------//
                                if (array_key_exists('features', $inputData)) {
                                    $productDataFeatures = $inputData['features'];
                                    $toExcludeFVRs = array($productId);
                                    $countQMarks = 0;
                                    $tempStrQmark = "";
                                    foreach ($productDataFeatures as $keyPDF => $valuePDF) {
                                        $whereForUpdateOrCreateFVR = ['product_id' => $productId, 'feature_id' => $keyPDF];
                                        $resultUorCFVR['code'] = 400;
                                        if (array_key_exists("single", $productDataFeatures[$keyPDF])) {
                                            $dataForUpdateOrCreateFVR = ['variant_ids' => 0, 'display_status' => $valuePDF['status']];
                                            $resultUorCFVR = json_decode($objModelProductFVR->updateOrCreateFVRWhere($whereForUpdateOrCreateFVR, $dataForUpdateOrCreateFVR), true);
                                        } else if (array_key_exists("multiple", $productDataFeatures[$keyPDF])) {
                                            $dataForUpdateOrCreateFVR = ['variant_ids' => implode(",", array_keys($valuePDF['multiple'])), 'display_status' => $valuePDF['status']];
                                            $resultUorCFVR = json_decode($objModelProductFVR->updateOrCreateFVRWhere($whereForUpdateOrCreateFVR, $dataForUpdateOrCreateFVR), true);
                                        } else if (array_key_exists("select", $productDataFeatures[$keyPDF])) {
                                            $dataForUpdateOrCreateFVR = ['variant_ids' => "" . $valuePDF['select'], 'display_status' => $valuePDF['status']];
                                            $resultUorCFVR = json_decode($objModelProductFVR->updateOrCreateFVRWhere($whereForUpdateOrCreateFVR, $dataForUpdateOrCreateFVR), true);
                                        }

                                        if ($resultUorCFVR['code'] != 200) {
                                            array_push($toExcludeFVRs, (int)$keyPDF);
                                            if ($countQMarks != 0) {
                                                $tempStrQmark .= ",";
                                            }
                                            $tempStrQmark .= "?";
                                            $countQMarks++;
                                        }
                                    }
                                    $whereForDelFVR = ['rawQuery' => "product_id = ? and feature_id IN ($tempStrQmark)", 'bindParams' => $toExcludeFVRs];
                                    $resultDeldFVR = $objModelProductFVR->deleteFeatureVariantRelationWhere($whereForDelFVR);
                                }
                                //------------------------PRODUCT FEATURES END HERE---------------------//

                                if (empty($errors)) {
                                    $returnData['code'] = 200;
                                    $returnData['message'] = "General details saved successfully";
                                } else {
                                    $returnData['code'] = 400;
                                    $returnData['message'] = "Data not updated. Something went wrong. Please try again.";
                                }
                                return redirect()->action(
                                    'Supplier\Controllers\ProductController@editProduct', ['productId' => $productId]
                                )->with($returnData);
                            }
                            break;

                        case "images":
                            $rules = [
                                'mainimage' => 'required|image|mimes:jpeg,bmp,png|max:1000'
                            ];
                            $messages['mainimage.required'] = 'Please select an image to upload.';
                            $messages['mainimage.image'] = 'Only jpg, jpeg, gif images allowed for upload.';
                            $validator = Validator::make($inputData, $rules, $messages);
                            if ($validator->fails()) {
                                return Redirect::back()
                                    ->with(["code" => 400, "status" => 'error', 'message' => 'Please correct the following errors.'])
                                    ->withErrors($validator)
                                    ->withInput();
                            } else {
                                //----------------------------PRODUCT-IMAGES------------------------------//
                                $productImages = $_FILES['product_data'];
                                $imageData = array();
                                if ($productImages['error']['mainimage'] == 0) {
                                    $mainImageURL = uploadImageToStoragePath($productImages['tmp_name']['mainimage'], 'product_' . $productId, 'product_' . $productId . '_0_' . time() . '.jpg', 724, 1024);
                                    if ($mainImageURL) {
                                        $mainImageData['for_product_id'] = $productId;
                                        $mainImageData['image_type'] = 0;
                                        $mainImageData['image_upload_type'] = 0;
                                        $mainImageData['image_url'] = $mainImageURL;
                                        $imageData[] = $mainImageData;
                                    }
                                } else {
                                    $errors[] = 'Sorry, something went wrong. Main image could not be uploaded, You can upload it on edit section.';
                                }

                                if (!empty($imageData)) {
                                    $objModelProductImage->addNewImage($imageData);
                                }
                                //--------------------------END PRODUCT-IMAGES----------------------------//

                                if (empty($errors)) {
                                    if (empty($imageData)) {
                                        $returnData['message'] = "Nothing to update";
                                        $returnData['code'] = 100;
                                    } else {
                                        $returnData['code'] = 200;
                                        $returnData['message'] = "Main image updated successfully";
                                    }
                                } else {
                                    $returnData['code'] = 400;
                                    $returnData['message'] = "Images not updated. Something went wrong. Please try again.";
                                }
                                return redirect()->action(
                                    'Supplier\Controllers\ProductController@editProduct', ['productId' => $productId]
                                )->with($returnData);

                            }
                            break;

                        case "options":

                            $rules = [
                                'options' => 'required'
                            ];
                            $messages['options.required'] = 'Please choose atleast one option.';
                            $validator = Validator::make($inputData, $rules, $messages);
                            if ($validator->fails()) {
                                return Redirect::back()
                                    ->with(["code" => 400, "status" => 'error', 'message' => 'Please correct the following errors.'])
                                    ->withErrors($validator)
                                    ->withInput();
                            } else {
                                //TODO options code here

                                //------------------------PRODUCT OPTIONS START HERE---------------------//
                                if (array_key_exists('options', $inputData)) {
                                    $editedOptions = $inputData['options'];
                                    $bindParamsForOVREdit = array_column($editedOptions, 'option_id');
//                                    $clauseForOVR = implode(',', array_fill(0, count($bindParamsForOVREdit) - 1, '?'));
//                                    array_unshift($bindParamsForOVREdit, $productId);
                                    $whereForOVREdit = ['rawQuery' => "status = 1 and product_id = ?", 'bindParams' => [$productId]];// and option_id IN ($clauseForOVR), $bindParamsForOVREdit
                                    $allOVRs = json_decode($objModelProductOptVarRel->getAllOptVarRelsWhere($whereForOVREdit), true);

//                                    array_shift($bindParamsForOVREdit);//edited options

                                    $existingOptionIds = (($allOVRs['code'] == 200) ? (array_column($allOVRs['data'], 'option_id', 'relation_id')) : array());

                                    $intersectOVR = array_intersect($existingOptionIds, $bindParamsForOVREdit);//for updating
                                    $diffNewOVR = array_diff($bindParamsForOVREdit, $existingOptionIds);//for inserting
//                                    dd($existingOptionIds);
                                    $diffDelOVR = array_diff($existingOptionIds, $bindParamsForOVREdit);//for deleting

                                    if (!empty($intersectOVR)) {
                                        //udpate OVRs [variant_ids and variant_data]
                                        $intersectEditedOpts = array_where($editedOptions, function ($keyEO, $valueEO) use ($intersectOVR) {
                                            return in_array($valueEO['relation_id'], array_keys($intersectOVR));
                                        });
                                        foreach ($intersectEditedOpts as $keyIEO => $valueIEO) {
                                            $where4UpdOVR = ['rawQuery' => 'product_id = ? and option_id = ?', 'bindParams' => [$productId, $valueIEO['option_id']]];

                                            $data4UpdOVR['status'] = $valueIEO['status'];

                                            $tempOptionVariantData = array();
                                            $variantIds = array();

                                            //-------------------------PRODUCT OPTION VARIANT START---------------------//
                                            //todo low:easy make this as a function or code block for avoid rewrite
                                            //todo med:med need validation that there is atleast one variant in every option
                                            //todo med:med 17/12/2016 need validation that atleast one of the options contain more than one variants // check if this validation is required
                                            //todo high:med check if those variants actually exists
                                            if (array_key_exists('variantData', $valueIEO)) {
                                                foreach ($valueIEO['variantData'] as $variantKey => $variantValue) {
                                                    $temp = array();
                                                    array_push($variantIds, $variantValue['variant_id']);
                                                    $temp['VID'] = $variantValue['variant_id'];
                                                    $temp['VN'] = $variantValue['variant_name'];
                                                    $temp['PM'] = $variantValue['price_modifier'];
                                                    $temp['PMT'] = $variantValue['price_modifier_type'];
                                                    $temp['WM'] = $variantValue['weight_modifier'];
                                                    $temp['WMT'] = $variantValue['weight_modifier_type'];
                                                    $temp['STTS'] = $variantValue['status'];
                                                    $tempOptionVariantData[] = $temp;
                                                }
                                            }
                                            if (array_key_exists('variantDataNew', $valueIEO)) {
                                                foreach ($valueIEO['variantDataNew'] as $variantKey => $variantValue) {
                                                    $temp = array();
                                                    $variantData['option_id'] = $valueIEO['option_id'];
                                                    $variantData['variant_name'] = $variantValue['variant_name'];
                                                    $variantData['added_by'] = $userId;
                                                    $variantData['status'] = $variantValue['status'];
                                                    $variantData['created_at'] = NULL;
                                                    $insertedVariantId = $objModelProductOptionVariant->addNewVariantAndGetID($variantData);
                                                    if ($insertedVariantId > 0) {
                                                        $varDataForCombinations[$variantValue['variant_id']] = $insertedVariantId;
                                                        array_push($variantIds, $insertedVariantId);
                                                        $temp['VID'] = $insertedVariantId;
                                                        $temp['VN'] = $variantValue['variant_name'];
                                                        $temp['PM'] = $variantValue['price_modifier'];
                                                        $temp['PMT'] = $variantValue['price_modifier_type'];
                                                        $temp['WM'] = $variantValue['weight_modifier'];
                                                        $temp['WMT'] = $variantValue['weight_modifier_type'];
                                                        $temp['STTS'] = $variantValue['status'];
                                                    }
                                                    $tempOptionVariantData[] = $temp;
                                                }
                                            }
                                            if (!empty($variantIds) && !empty($tempOptionVariantData)) {
                                                $data4UpdOVR['variant_ids'] = implode(',', $variantIds);
                                                $data4UpdOVR['variant_data'] = json_encode($tempOptionVariantData);
                                            }
                                            //-------------------------PRODUCT OPTION VARIANT END---------------------//

                                            $objModelProductOptVarRel->updateOVRWhere($data4UpdOVR, $where4UpdOVR);
                                        }

                                    }

                                    if (empty($diffNewOVR) && empty($diffDelOVR)) {
                                        //update combination data
                                        //------------------------PRODUCT OPTION COMBINATIONS START HERE---------------------//
                                        if (count($inputData['options']) > 1) {
                                            if (array_key_exists('opt_combination', $inputData)) {
                                                //todo high:diff validation required [call generateCombinations & compare data from view]
                                                foreach ($inputData['opt_combination']['existing'] as $keyCombination => $valueCombination) {
                                                    //TODO ADD BARCODE, shipping info and image data for the combination here
                                                    $where4UpdOVC = ['rawQuery' => 'product_id = ? and variant_ids = ? and combination_id = ?', 'bindParams' => [$productId, $keyCombination, $valueCombination['combination_id']]];

                                                    $data4UpdOVC['quantity'] = $valueCombination['quantity'];
                                                    $data4UpdOVC['exception_flag'] = 0;
                                                    if (isset($valueCombination['excludeflag']) && $valueCombination['excludeflag'] == 'on') {
                                                        $data4UpdOVC['exception_flag'] = 1;
                                                    }
                                                    $objModelProductOptVarCombination->updateCombinationWhere($data4UpdOVC, $where4UpdOVC);
                                                }
                                            } else {
                                                DB::rollBack();
                                                return Redirect::back()
                                                    ->with(["status" => 'error', 'msg' => 'Something went wrong while generating combinations. Please try again.'])
                                                    ->withErrors($validator)
                                                    ->withInput();
                                            }
                                        }
                                        //------------------------PRODUCT OPTION COMBINATIONS END HERE---------------------//
                                    } else if (!empty($diffNewOVR) || !empty($diffDelOVR)) {
                                        $varDataForCombinations = array();
                                        if (!empty($diffNewOVR)) {
                                            $diffnewOpts = array_where($editedOptions, function ($keyEO, $valueEO) use ($diffNewOVR) {
                                                return in_array($valueEO['option_id'], $diffNewOVR);
                                            });

                                            $finalOptVarRelData = array();
                                            foreach ($diffnewOpts as $keyDiffOVR => $valueDiffOVR) {
                                                $dataOptVarRel['product_id'] = $productId;
                                                $dataOptVarRel['option_id'] = $valueDiffOVR['option_id'];
                                                $dataOptVarRel['status'] = $valueDiffOVR['status'];

                                                $tempOptionVariantData = array();
                                                $variantIds = array();

                                                //-------------------------PRODUCT OPTION VARIANT START---------------------//
                                                //todo need validation that there is atleast one variant in every option
                                                //todo med:med 17/12/2016 need validation that atleast one of the options contain more than one variants // check if this validation is required
                                                if (array_key_exists('variantData', $valueDiffOVR)) {
                                                    foreach ($valueDiffOVR['variantData'] as $variantKey => $variantValue) {
                                                        $temp = array();
                                                        array_push($variantIds, $variantValue['variant_id']);
                                                        $temp['VID'] = $variantValue['variant_id'];
                                                        $temp['VN'] = $variantValue['variant_name'];
                                                        $temp['PM'] = $variantValue['price_modifier'];
                                                        $temp['PMT'] = $variantValue['price_modifier_type'];
                                                        $temp['WM'] = $variantValue['weight_modifier'];
                                                        $temp['WMT'] = $variantValue['weight_modifier_type'];
                                                        $temp['STTS'] = $variantValue['status'];
                                                        $tempOptionVariantData[] = $temp;
                                                    }
                                                }
                                                if (array_key_exists('variantDataNew', $valueDiffOVR)) {
                                                    foreach ($valueDiffOVR['variantDataNew'] as $variantKey => $variantValue) {
                                                        $temp = array();
                                                        $variantData['option_id'] = $valueDiffOVR['option_id'];
                                                        $variantData['variant_name'] = $variantValue['variant_name'];
                                                        $variantData['added_by'] = $userId;
                                                        $variantData['status'] = $variantValue['status'];
                                                        $variantData['created_at'] = NULL;
                                                        $insertedVariantId = $objModelProductOptionVariant->addNewVariantAndGetID($variantData);
                                                        if ($insertedVariantId > 0) {
                                                            $varDataForCombinations[$variantValue['variant_id']] = $insertedVariantId;
                                                            array_push($variantIds, $insertedVariantId);
                                                            $temp['VID'] = $insertedVariantId;
                                                            $temp['VN'] = $variantValue['variant_name'];
                                                            $temp['PM'] = $variantValue['price_modifier'];
                                                            $temp['PMT'] = $variantValue['price_modifier_type'];
                                                            $temp['WM'] = $variantValue['weight_modifier'];
                                                            $temp['WMT'] = $variantValue['weight_modifier_type'];
                                                            $temp['STTS'] = $variantValue['status'];
                                                        }
                                                        $tempOptionVariantData[] = $temp;
                                                    }
                                                }
                                                if (!empty($variantIds) && !empty($tempOptionVariantData)) {
                                                    $dataOptVarRel['variant_ids'] = implode(',', $variantIds);
                                                    $dataOptVarRel['variant_data'] = json_encode($tempOptionVariantData);
                                                }
                                                //-------------------------PRODUCT OPTION VARIANT END---------------------//

                                                $finalOptionVariantRelationData[] = $dataOptVarRel;
                                            }
                                            if (!empty($finalOptVarRelData)) {
                                                $objModelProductOptVarRel->addNewOptionVariantRelation($finalOptVarRelData);
                                            }

                                        }
                                        if (!empty($diffDelOVR)) {
                                            //delete OVRs
                                            $clause4DelOVR = implode(',', array_fill(0, count($diffDelOVR) - 1, '?'));
                                            array_unshift($diffDelOVR, $productId);
                                            $where4DelOVR = ['rawQuery' => "product_id = ? and option_id IN ($clause4DelOVR)", 'bindParams' => $diffDelOVR];
                                            $objModelProductOptVarRel->deleteOVRWhere($where4DelOVR);

                                        }
                                        //delete OVCs
                                        $objModelProductOptVarCombination->deleteCombinationWhere(['rawQuery' => 'product_id = ?', 'bindParams' => [$productId]]);

                                        //add OVCs
                                        //------------------------PRODUCT OPTION COMBINATIONS START HERE---------------------//
                                        if (count($inputData['options']) > 1) {
                                            if (array_key_exists('opt_combination', $inputData)) {
                                                //todo high:diff validation required [call generateCombinations & compare data from view]
                                                foreach ($inputData['opt_combination']['existing'] as $keyCombination => $valueCombination) {
                                                    //TODO ADD BARCODE, shipping info and image data for the combination here
                                                    $dataCombinations['product_id'] = $productId;
                                                    $dataCombinations['variant_ids'] = $keyCombination;
                                                    $dataCombinations['quantity'] = $valueCombination['quantity'];
                                                    $dataCombinations['exception_flag'] = 0;
                                                    if (isset($valueCombination['excludeflag']) && $valueCombination['excludeflag'] == 'on') {
                                                        $dataCombinations['exception_flag'] = 1;
                                                    }
                                                    $objModelProductOptVarCombination->addNewOptionVariantsCombination($dataCombinations);
                                                }
                                                if (array_key_exists('new', $inputData['opt_combination'])) {
                                                    foreach ($inputData['opt_combination']['new'] as $keyCombination => $valueCombination) {
                                                        $flags = explode("_", $valueCombination['newflag']);
                                                        $combinationVarIds = explode("_", $keyCombination);
                                                        $flagKeys = array_keys($flags, "1");
                                                        if (!empty($flagKeys)) {
                                                            foreach ($flagKeys as $keyFK => $valueFK) {
                                                                $combinationVarIds[$keyFK] = $varDataForCombinations[$combinationVarIds[$keyFK]];
                                                            }
                                                        }
                                                        //TODO low:easy ADD BARCODE, shipping info and image data for the combination here
                                                        $dataCombinations['product_id'] = $productId;
                                                        $dataCombinations['variant_ids'] = implode("_", $combinationVarIds);
                                                        $dataCombinations['quantity'] = $valueCombination['quantity'];
                                                        $dataCombinations['exception_flag'] = 0;
                                                        //todo med:med add code for image for combination here
                                                        if (isset($valueCombination['excludeflag']) && $valueCombination['excludeflag'] == 'on') {
                                                            $dataCombinations['exception_flag'] = 1;
                                                        }
                                                        $objModelProductOptVarCombination->addNewOptionVariantsCombination($dataCombinations);
                                                    }
                                                }
                                            } else {
                                                DB::rollBack();
                                                return Redirect::back()
                                                    ->with(["status" => 'error', 'msg' => 'Something went wrong while generating combinations. Please try again.'])
                                                    ->withErrors($validator)
                                                    ->withInput();
                                            }
                                        }
                                        //------------------------PRODUCT OPTION COMBINATIONS END HERE---------------------//
                                    }
                                    //------------------------PRODUCT OPTION COMBINATIONS END HERE---------------------//
                                }
                                //------------------------PRODUCT OPTIONS END HERE---------------------//


                                $returnData['code'] = 200;
                                $returnData['message'] = "Options saved successfully";
                                $returnData['data'] = null;
                            }

                            break;

                        case "shipping":
                            $whereForProdUpdate = ['rawQuery' => 'product_id = ?', 'bindParams' => [$productId]];

                            $shippingParams = array();
//                                $shippingParams['min_items'] = $inputData['shipping_properties']['min_items'];
//                                $shippingParams['max_items'] = $inputData['shipping_properties']['min_items'];

                            if (array_key_exists('box_length', $inputData['shipping_properties']))
                                $shippingParams['box_length'] = $inputData['shipping_properties']['box_length'];
                            if (array_key_exists('box_width', $inputData['shipping_properties']))
                                $shippingParams['box_width'] = $inputData['shipping_properties']['box_width'];
                            if (array_key_exists('box_height', $inputData['shipping_properties']))
                                $shippingParams['box_height'] = $inputData['shipping_properties']['box_height'];
                            $productMetaData['shipping_freight'] = $inputData['shipping_properties']['shipping_freight'];
                            $productMetaData['weight'] = $inputData['shipping_properties']['weight'];

                            $productMetaData['shipping_params'] = json_encode($shippingParams);
                            $resultUpdatedProdMetadata = json_decode($objModelProductMeta->updateProdMetadataWhere($productMetaData, $whereForProdUpdate), true);

                            if ($resultUpdatedProdMetadata['code'] == 200) {
                                $returnData['code'] = 200;
                                $returnData['message'] = "Shipping details updated successfully";
                            } else {
                                $returnData['code'] = 400;
                                $returnData['message'] = "Data not updated. Something went wrong. Please try again.";
                            }
                            return redirect()->action(
                                'Supplier\Controllers\ProductController@editProduct', ['productId' => $productId]
                            )->with($returnData);
                            break;

                        case "discounts":
                            $whereForProdUpdate = ['rawQuery' => 'product_id = ?', 'bindParams' => [$productId]];
                            $productMetaData['quantity_discount'] = json_encode($inputData['quantity_discount']);
                            $resultUpdatedProdMetadata = json_decode($objModelProductMeta->updateProdMetadataWhere($productMetaData, $whereForProdUpdate), true);
                            if ($resultUpdatedProdMetadata['code'] == 200) {
                                $returnData['code'] = 200;
                                $returnData['message'] = "Discount details updated successfully";
                            } else {
                                $returnData['code'] = 400;
                                $returnData['message'] = "Data not updated. Something went wrong. Please try again.";
                            }
                            return redirect()->action(
                                'Supplier\Controllers\ProductController@editProduct', ['productId' => $productId]
                            )->with($returnData);

                            break;

                        case "filters":
                            //todo high:med update filter data here
                            /*
                            $rules = [
                                'mainimage' => 'image|mimes:jpeg,bmp,png|max:1000'
                            ];
                            $messages['mainimage.image'] = 'Only jpg, jpeg, gif images allowed for upload.';
                            $validator = Validator::make($inputData, $rules, $messages);
                            if ($validator->fails()) {
                                return Redirect::back()
                                    ->with(["code" => 400, "status" => 'error', 'message' => 'Please correct the following errors.'])
                                    ->withErrors($validator)
                                    ->withInput();
                            } else {
                                $returnData['code'] = 200;
                                $returnData['message'] = "Filters saved successfully";
                                $returnData['data'] = null;
                            }
                            */
                            break;

                        case "tabs":
                            $whereForProdUpdate = ['rawQuery' => 'product_id = ?', 'bindParams' => [$productId]];
                            $productMetaData['product_tabs'] = json_encode($inputData['product_tabs']);
                            $resultUpdatedProdMetadata = json_decode($objModelProductMeta->updateProdMetadataWhere($productMetaData, $whereForProdUpdate), true);
                            if ($resultUpdatedProdMetadata['code'] == 200) {
                                $returnData['code'] = 200;
                                $returnData['message'] = "Tabs details updated successfully";
                            } else {
                                $returnData['code'] = 400;
                                $returnData['message'] = "Data not updated. Something went wrong. Please try again.";
                            }
                            return redirect()->action(
                                'Supplier\Controllers\ProductController@editProduct', ['productId' => $productId]
                            )->with($returnData);
                            break;

                        default:

                            break;
                    }
                } else if ($request->files) {
                    $otherImage = $_FILES['otherimage'];
                    if (isset($otherImage['name']) && $productId != '') {
                        $whereForProdImgLast = ['rawQuery' => "for_product_id = ?", 'bindParams' => [$productId]];
                        $selColsForProdImgLast = ['*'];
                        $sortByForProdImgLast = ['column' => 'product_images.pi_id', 'order' => 'desc'];
                        $resultProdImgLast = json_decode($objModelProductImage->getImageWhere($whereForProdImgLast, $selColsForProdImgLast, $sortByForProdImgLast), true);
                        $otherImageKey = 0;
                        if ($resultProdImgLast['code'] == 200) {
                            $explodedImageUrl = explode('/', $resultProdImgLast['data']['image_url']);
                            $otherImageKey = explode('_', end($explodedImageUrl))[2];
                        }
                        $otherImageURL = uploadImageToStoragePath($otherImage['tmp_name'], 'product_' . $productId, 'product_' . $productId . '_' . ($otherImageKey + 1) . '_' . time() . '.jpg', 724, 1024);
                        if ($otherImageURL) {
                            $otherImageData['for_product_id'] = $productId;
                            $otherImageData['image_type'] = 1;
                            $otherImageData['image_upload_type'] = 0;
                            $otherImageData['image_url'] = $otherImageURL;
                            $imageData[] = $otherImageData;
                            $resultImageUpdated = json_decode($objModelProductImage->addImage($imageData), true);
                            if ($resultImageUpdated['code'] == 200) {
                                die('{"result" : "TRUE", "imageURL" : "' . $otherImageURL . '", "id" : "id", "imageId" : "' . $resultImageUpdated['data'] . '"}');
                            }
                        }
                        $oldFile = $_SERVER['DOCUMENT_ROOT'] . $otherImageURL;
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                    die('{"result" : "FALSE"}');
                }
            }
            $whereForCat = ['rawQuery' => 'category_status =?', 'bindParams' => [1]];
            $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);

            $whereForFeatureGroup = ['rawQuery' => 'group_flag =? and status = ?', 'bindParams' => [1, 1]];
            $allFeatureGroups = $objModelFeatures->getAllFeaturesWhere($whereForFeatureGroup);

            //GET from product_feature_variant_relation
            $catId = (int)$productData['data']['category_id'];
            $catFlag = true;
            $parentCategory = array();
            $count = 1;
            $bindParamsForFeature = array();
            $queryForFeature = "";
            $queryForFeatureGroup = "";
            while ($catFlag) {
                if ($count == 1) {
                    $queryForFeatureGroup = '(product_features.group_flag = 1) and (product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? ';
                    $queryForFeature = '(group_flag = 0 and parent_id = 0) and (for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? ';
                } else {
                    $catId = $parentCategory['data']['parent_category_id'];
                    $queryForFeatureGroup .= 'OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? OR product_features.for_categories LIKE ? ';
                    $queryForFeature .= 'OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? OR for_categories LIKE ? ';
                }
                $count++;
                array_push($bindParamsForFeature, "%,$catId");
                array_push($bindParamsForFeature, "%,$catId,%");
                array_push($bindParamsForFeature, "$catId,%");
                array_push($bindParamsForFeature, "$catId");
                $parentCategory = array();
                $whereForCat = ['rawQuery' => 'category_id = ? and category_status = 1', "bindParams" => [$catId]];
                $parentCategory = json_decode($objModelCategory->getCategoryWhere($whereForCat), true);
                if ($parentCategory['data'] == NULL) {
                    $catFlag = false;
                }
            }
            $queryForFeature .= ")";
            $queryForFeatureGroup .= ")";
            $whereForFeature = ['rawQuery' => $queryForFeature, 'bindParams' => $bindParamsForFeature];
            $featureDetails = json_decode($objModelProductFeature->getAllFeaturesWithVariantsWhere($whereForFeature, ['*'], $productId), true);
//            dd($featureDetails);
            $whereForFeatureGroup = ['rawQuery' => $queryForFeatureGroup, 'bindParams' => $bindParamsForFeature];
            $featureGroups = json_decode($objModelProductFeature->getAllFGsWithFsWhere($whereForFeatureGroup), true);
            foreach ($featureGroups['data'] as $keyFG => $valueFG) {
                $whereForFs = ['rawQuery' => "product_features.parent_id IN (?)", "bindParams" => [$valueFG['feature_ids']]];
                $featureGroups['data'][$keyFG]['featureDetails'] = json_decode($objModelProductFeature->getAllFeaturesWithVariantsWhere($whereForFs, ['*'], $productId), true)['data'];
            }
//            dd($featureGroups);
//            $whereForFVRelation = ['rawQuery' => "product_id = ?", 'bindParams' => [$productId]];
//            $fvRelations = json_decode($objModelProductFVR->getAllFeatureVariantRelationsWhere($whereForFVRelation), true);
//            dd($fvRelations);

            //GET from options
            $whereForOptions = ['rawQuery' => 'status = 1'];
            $allOptions = $objModelProductOption->getAllOptionsWhere($whereForOptions);

            //GET from option_variants
//            $objModelProductOptionVariant->getVariants

            //GET from option_variants + option_variant_relation
            $whereForOptVar = ['rawQuery' => "1"];//product_id = ?", 'bindParams' => [$productId]];
            $whereForJoin = ['column' => 'product_id', 'condition' => '=', 'value' => "$productId"];
            $dataOptVarWithRelations = json_decode($objModelProductOptionVariant->getOptionVarWithRelationsWhere($whereForOptVar, ['*'], $whereForJoin), true);
//            dd($dataOptVarWithRelations);

            //GET from option_variant_relations
            $whereForOptVarRel = ['rawQuery' => 'product_id = ?', 'bindParams' => [$productId]];
            $selColsForOptVarRel = [DB::raw('GROUP_CONCAT(product_option_variant_relation.option_id ORDER BY product_option_variant_relation.option_id) as all_option_ids'), DB::raw('GROUP_CONCAT(product_options.option_name ORDER BY product_option_variant_relation.option_id) as all_option_names'), DB::raw('GROUP_CONCAT(variant_ids ORDER BY product_option_variant_relation.option_id) as all_variant_ids'), DB::raw('REPLACE(GROUP_CONCAT(`product_option_variant_relation`.`variant_data` ORDER BY product_option_variant_relation.option_id SEPARATOR "____"), "]____[", ",") as all_variant_data')];
//            $selColsForOptVarRel = [DB::raw('GROUP_CONCAT(product_option_variant_relation.option_id ORDER BY product_option_variant_relation.option_id) as all_option_ids'), DB::raw('GROUP_CONCAT(product_options.option_name ORDER BY product_option_variant_relation.option_id SEPARATOR "____") as all_option_names'), DB::raw('GROUP_CONCAT(variant_ids ORDER BY product_option_variant_relation.option_id SEPARATOR "____") as all_variant_ids'), DB::raw('GROUP_CONCAT(`product_option_variant_relation`.`variant_data` ORDER BY product_option_variant_relation.option_id SEPARATOR "____") as all_variant_data')];
            $dataOptVarRel = json_decode($objModelProductOptVarRel->getOptVarRelsWhere($whereForOptVarRel, $selColsForOptVarRel), true);
//            dd($dataOptVarRel);
            if ($dataOptVarRel['code'] == 200) {
                $dataOptVarRel['data']['all_variant_data'] = json_decode($dataOptVarRel['data']['all_variant_data'], true);
                $dataOptVarRel['data']['all_option_names'] = explode(",", $dataOptVarRel['data']['all_option_names']);
            }
            $varFlagString = array();
            $countSelOpts = count(explode(",", $dataOptVarRel['data']['all_option_ids']));
            while ($countSelOpts > 0) {
                array_push($varFlagString, 0);
                $countSelOpts--;
            }
            $varFlagString = implode("_", $varFlagString);
//            dd($varFlagString);

            //GET from option_variants_combination
            $whereForOptVarCombinations = ['rawQuery' => "product_option_variants_combination.product_id = ?", 'bindParams' => [$productId]];
//            $selColsForOptVatCombs = ['product_option_variants_combination.*', DB::raw('REPLACE(GROUP_CONCAT(`product_option_variant_relation`.`variant_data` SEPARATOR ","), "],[", ",") as temp')];// todo
            $selColsForOptVatCombs = ['product_option_variants_combination.*', DB::raw("'$varFlagString' as varFlagString")];//, DB::raw("case when 'variant_id' IN (select REPLACE(SUBSTRING(SUBSTRING_INDEX(variant_ids, \"_\", \"1\"), LENGTH(SUBSTRING_INDEX(variant_ids, \"_\", 1-1)) + 1), \"_\", \",\")) then product_option_variants.option_id END as temp")
            $dataOptVarCombs = json_decode($objModelProductOptVarCombination->getAllCombinationsWhere($whereForOptVarCombinations, $selColsForOptVatCombs), true);
            if ($dataOptVarCombs['code'] == 200 && $dataOptVarRel['code'] == 200) {
//                $dataOptVarRel['data']['all_variant_data'] = explode("____", $dataOptVarRel['data']['all_variant_data']);
//                array_walk($dataOptVarRel['data']['all_variant_data'], function (&$testValue, $testKey) {
//                    $testValue = json_decode($testValue, true);
//                });

                foreach ($dataOptVarCombs['data'] as $keyDOVC => $valueDOVC) {
                    $tempVarIds = explode("_", $valueDOVC['variant_ids']);
                    $tempNameString = '';
                    array_walk($tempVarIds, function (&$valueTempVarIdKey, $keyTempVarIdKey) use ($dataOptVarRel, &$tempNameString) {
                        $keyASD = array_search("$valueTempVarIdKey", array_column($dataOptVarRel['data']['all_variant_data'], 'VID', 'VN'));
                        $tempNameString .= $dataOptVarRel['data']['all_option_names'][$keyTempVarIdKey] . " : " . $keyASD . " <br/> ";
                    });
                    $dataOptVarCombs['data'][$keyDOVC]['nameString'] = $tempNameString;
                }
            }
//            dd($dataOptVarCombs);

            //GET from product_images
            $whereForImages = ['rawQuery' => 'for_product_id = ? and for_combination_id = ?', 'bindParams' => [$productId, 0]];
            $dataDefaultImages = json_decode($objModelProductImage->getAllImagesWhere($whereForImages), true);
//            dd($dataDefaultImages);

            foreach ($allCategories as $key => $value) {
                $allCategories[$key]->display_name = $this->getCategoryDisplayName($value->category_id);
            }


            $whereForShops = ['rawQuery' => 'shop_status IN (1,2) and parent_category_id = 0'];
            $selColsForShops = ['shops.*', 'users.name', 'users.last_name', 'users.username', 'users.status'];
            $resultAllShops = json_decode($objModelShops->getAllShopsWhere($whereForShops,$selColsForShops), true);

            return view('Supplier/Views/product/editProduct', ['code' => $returnData['code'], 'allCategories' => $allCategories, 'allOptions' => $allOptions, 'featuresData' => ['featureGroupDetails' => $featureGroups, 'featureDetails' => $featureDetails/*, 'fvRelations' => $fvRelations*/], 'productData' => $productData['data'], 'dataOptVarWithRelations' => $dataOptVarWithRelations, 'dataOptVarCombs' => $dataOptVarCombs, 'dataOptVarRel' => $dataOptVarRel, 'dataDefaultImages' => $dataDefaultImages, 'allShops'=>$resultAllShops]);//, 'message' => $returnData['message'], 'status' => $returnData['code'] == 200 ? "success" : "error"
        } else {
            return view('Supplier/Views/product/editProduct', ['code' => '400', 'message' => 'No such product exists.', 'productData' => array()]);
        }

    }

}
