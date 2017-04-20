<?php

namespace FlashSaleApi\Http\Controllers\Order;

use FlashSaleApi\Http\Models\Orders;
use FlashSaleApi\Http\Models\ProductOptionVariants;
//use FlashSaleApi\Http\Models\Transactions;
use FlashSaleApi\Http\Models\Transactions;
use Illuminate\Http\Request;
use FlashSaleApi\Http\Requests;
use FlashSaleApi\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use PDO;
use FlashSaleApi\Http\Models\Products;
use FlashSaleApi\Http\Models\User;
use stdClass;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
//    public function __call(){
//
//    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        return view("Admin\admin")
    }


    /**
     * insert-order method for inserting cart orders into orders table
     * user-cart-details method for getting users cart products to cart page
     * remove-cart-detail method for removing cart product from cart page
     * @param Request $request
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function orderAjaxHandler(Request $request)
    {
        $method = $request->input('method');
        $response = new stdClass();
        $objProductModel = Products::getInstance();
        $objOptionVariant = ProductOptionVariants::getInstance();
        $objOrderModel = Orders::getInstance();
        $objModelOrders = Orders::getInstance();
        $objModelUsers = Orders::getInstance();
        if ($method != "") {
            switch ($method) {

                case 'insert-order':
                    $postData = $request->all();
                    $response = new stdClass();
                    $objUserModel = new User();
                    if ($postData) {
                        $userId = '';
                        $mainCartData = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }

                        if (isset($postData['mainCartData'])) {
                            $mainCartData = $postData['mainCartData'];

                        }
                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;

                            } else {
                                if ($userId != '') {
                                    $whereForloginToken = $whereForUpdate = [
                                        'rawQuery' => 'id =?',
                                        'bindParams' => [$userId]
                                    ];
                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                                    if ($mytoken == $Userscredentials->login_token) {
                                        $authflag = true;
                                    }
                                }
                            }
                        }
                        if ($authflag) {//LOGIN TOKEN
                            $objOrderModel = Orders::getInstance();
                            if (json_decode($mainCartData, true)) {
                                foreach (json_decode($mainCartData, true) as $key => $val) {
                                    if (sizeof($val) > 0) {
                                        $product_id[$key] = $val['product_id'];
                                    } else {
                                        $quantity[$key] = $val['quantity'];
                                        $product_id[$key] = $val['product_id'];
                                    }
                                }
                                $like = implode(' OR ', array_map(function ($v) {
                                    return 'product_id LIKE "%' . $v . '%"';
                                }, $product_id));
                                $where = ['rawQuery' => 'for_user_id = ? AND  (' . $like . ') AND order_status = "P"', 'bindParams' => [$userId]];
                                $selectOrder = $objOrderModel->getcartOrder($where);
                                if ($selectOrder) {
                                    $updatedData = [];
                                    $newData = [];
                                    $productIDs = [];
                                    foreach ($selectOrder as $orderKey => $orderVal) {

                                        $productIDs[] = $orderVal->product_id;
                                        $updatedData[] = current(array_values(array_filter(array_map(function ($v) use ($orderVal) {
                                            if (in_array($orderVal->product_id, $v) && in_array($orderVal->for_user_id, $v)) {
                                                $orderVal->quantity = $v['quantity'] + $orderVal->quantity;
                                                return $orderVal;
                                            }
                                        }, json_decode($mainCartData, true)))));

                                    }
                                    if (!empty($updatedData)) {
                                        $in = implode(',', array_values(array_filter(array_map(function ($v) {
                                            return $v->order_id;
                                        }, $updatedData))));
                                        $case = implode(' ', array_map(function ($v) {
                                            return ' WHEN ' . $v->order_id . ' THEN ' . $v->quantity;
                                        }, $updatedData));

                                        $whereUpdated = ['rawQuery' => 'order_id IN (' . $in . ')'];
                                        $updatedResult = $objOrderModel->updateToOrder(['quantity' => DB::raw("(CASE order_id $case END)")], $whereUpdated);

                                    }
                                    $productIDs = array_unique($productIDs);
                                    $newData = array_values(array_filter(array_map(function ($cv) use ($productIDs) {
                                        if (!in_array($cv['product_id'], $productIDs))
                                            return $cv;
                                    }, json_decode($mainCartData, true))));
                                    if (!empty($newData)) {
                                        $insertOrder = $objOrderModel->insertToOrder($newData);
                                    }
                                    if ((isset($insertOrder) && $insertOrder != '') || (isset($updatedResult) && $updatedResult != '')) {
                                        $response->code = 200;
                                        $response->message = isset($insertOrder) ? 'Added to cart' : 'Updated to cart';
                                        $response->data = null;

                                    } else {
                                        $response->code = 400;
                                        $response->message = "Nothing Cart details.";
                                        $response->data = null;
                                    }
                                } else if (empty($selectOrder)) {
                                    $insertOrder = $objOrderModel->insertToOrder(json_decode($mainCartData, true));
                                    if ($insertOrder) {
                                        $response->code = 200;
                                        $response->message = "Success";
                                        $response->data = $insertOrder;
                                    } else {
                                        $response->code = 400;
                                        $response->message = "No user Details found.";
                                        $response->data = null;
                                    }
                                }
                            }
                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;
                case 'getCartCount': // TODO : This method is  not to be used //
                    $postData = $request->all();
                    $response = new stdClass();
                    $objUserModel = new User();
                    if ($postData) {
                        $userId = '';
                        $mainCartData = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }

                        if (isset($postData['mainCartData'])) {
                            $mainCartData = $postData['mainCartData'];

                        }
                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;

                            } else {
                                if ($userId != '') {
                                    $whereForloginToken = $whereForUpdate = [
                                        'rawQuery' => 'id =?',
                                        'bindParams' => [$userId]
                                    ];
                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                                    if ($mytoken == $Userscredentials->login_token) {
                                        $authflag = true;
                                    }
                                }
                            }
                        }
                        if ($authflag) {//LOGIN TOKEN
                            $objOrderModel = Orders::getInstance();
                            $where = ['rawQuery' => 'for_user_id = ?', 'bindParams' => [$userId]];
                            $cartCount = $objOrderModel->getcartOrder($where);
                            if (isset($cartCount)) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = count($cartCount);
                            } else {
                                $response->code = 400;
                                $response->message = "No Details found.";
                                $response->data = null;

                            }
                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;
                case 'user-cart-details':
                    $postData = $request->all();
                    $response = new stdClass();
                    $objUserModel = new User();
                    if ($postData) {
                        $userId = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }
                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;

                            } else {
                                if ($userId != '') {
                                    $whereForloginToken = $whereForUpdate = [
                                        'rawQuery' => 'id =?',
                                        'bindParams' => [$userId]
                                    ];
                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                                    if ($mytoken == $Userscredentials->login_token) {
                                        $authflag = true;
                                    }
                                }
                            }
                        }
                        if ($authflag) {//LOGIN TOKEN
                            $objOrderModel = Orders::getInstance();
                            $whereUserId = ['rawQuery' => 'orders.for_user_id = ? AND orders.order_status = "P" AND
                            (product_option_variants_combination.variant_ids LIKE CONCAT("%",REPLACE(SUBSTRING_INDEX(orders.product_id,"-",-1),",","_"),"%")
                             or product_option_variants_combination.variant_ids LIKE CONCAT("%",REVERSE(REPLACE(SUBSTRING_INDEX(orders.product_id,"-",-1),",","_")),"%"))',
                                'bindParams' => [$userId]];
                            $selectColumn = [
                                DB::raw("SUBSTRING_INDEX(orders.product_id,'-',1) as pid ,REPLACE(SUBSTRING_INDEX(orders.product_id,'-',-1),',','_') cid "),
//                                'product_images.*',//todo remove comment and check functionality again
                                'products.product_name',
                                'products.price_total',
                                'products.in_stock',
                                'orders.quantity',
                                'orders.order_id',
                                'product_option_variant_relation.*',
                                'productmeta.quantity_discount',
                                'users.email', 'users.name', 'users.last_name', 'users.username', 'users.role',
                                DB::raw('GROUP_CONCAT(product_option_variant_relation.variant_data SEPARATOR "____") AS variant_datas')

                            ];
                            $cartProductDetails = json_decode($objOrderModel->getCartProductDetails($whereUserId, $selectColumn), true);
//                            dd($cartProductDetails);
                            $combineVarian = [];
                            $finalCartProductDetails = [];
                            $subTotal = '';
                            if ($cartProductDetails['code'] == 200) {
                                foreach (json_decode(json_encode($cartProductDetails['data']), false) as $cartkey => $cartVal) {
                                    if ($cartVal->in_stock >= $cartVal->quantity) {
                                        $variantData = explode("____", $cartVal->variant_datas);
                                        $varian = array_flatten(array_map(function ($v) {
                                            return json_decode($v);
                                        }, $variantData));

                                        $finalPrice = $cartVal->price_total;
                                        $combineVarian[] = array_values(array_filter(array_map(function ($v) use ($varian, &$finalPrice) {
                                            return current(array_filter(array_map(function ($value) use ($v, &$finalPrice) {
                                                if ($v == $value->VID) {
                                                    $finalPrice = $finalPrice + $value->PM;
                                                    return [$v => $value->PM];
                                                }
                                            }, $varian)));
                                        }, explode("_", $cartVal->cid))));
                                        $cartVal->finalPrice = $finalPrice * $cartVal->quantity;

                                        $discountedValue = 0;
                                        $qtyValue = null;
                                        if ($cartVal->quantity_discount != '') {
                                            $quantityDiscount = object_to_array(json_decode($cartVal->quantity_discount));
                                            $quantities = array_column($quantityDiscount, 'quantity');

                                            $quantity = $cartVal->quantity;
                                            $tmpQTY = array_filter(array_map(function ($v) use ($quantity) {
                                                if ($quantity >= $v) return $v;
                                            }, $quantities));
                                            sort($tmpQTY);
                                            $finalQTY = end($tmpQTY);

                                            $qtyValue = current(array_values(array_filter(array_map(function ($v) use ($finalQTY) {
                                                if ($v['quantity'] == $finalQTY) return $v;
                                            }, $quantityDiscount))));

                                            $discountedValue = ($qtyValue['type'] == 1) ? $qtyValue['value'] : ($cartVal->finalPrice * $qtyValue['value'] / 100);
                                            $discountedPrice = $cartVal->finalPrice - $discountedValue;


                                            if ($discountedPrice <= 0) {
                                                $error[] = 'Discount not allowed';
                                            }
                                            $cartVal->discountedPrice = $discountedPrice;
                                        }

                                        $cartVal->shipingPrice = 9; // TODO : NEED TO GET CONFIRMATION FOR SHIPPING DETAILS//

                                        $finalCartProductDetails[] = $cartVal;
                                        $subTotal = $subTotal + (isset($cartVal->discountedPrice) ? $cartVal->discountedPrice : $cartVal->finalPrice);

                                    } else {
                                        $error[] = 'Selected quantity should be greater than In-Stock';
                                    }
                                }
                                if ($subTotal != '' && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->subtotal = $subTotal;
                                }
                                $finalCartProductDetails[0]->cartcount = count($finalCartProductDetails);
                            }
                            if (isset($finalCartProductDetails)) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $finalCartProductDetails;
                            } else {
                                $response->code = 400;
                                $response->message = "No Details found.";
                                $response->data = null;

                            }
                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;
                case 'remove-cart-detail':
                    $postData = $request->all();
                    $response = new stdClass();
                    $objUserModel = new User();
                    if ($postData) {
                        $userId = '';
                        $mainCartData = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }

                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;

                            } else {
                                if ($userId != '') {
                                    $whereForloginToken = $whereForUpdate = [
                                        'rawQuery' => 'id =?',
                                        'bindParams' => [$userId]
                                    ];
                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                                    if ($mytoken == $Userscredentials->login_token) {
                                        $authflag = true;
                                    }
                                }
                            }
                        }
                        if ($authflag) {//LOGIN TOKEN
                            $objOrderModel = Orders::getInstance();
                            $whereCart = ['rawQuery' => 'order_id = ? AND for_user_id = ?', 'bindParams' => [$postData['order_id'], $userId]];
                            $cartData = $objOrderModel->deleteCartOrder($whereCart);
                            if (isset($cartData)) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $cartData;
                            } else {
                                $response->code = 400;
                                $response->message = "No Details found.";
                                $response->data = null;

                            }
                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;
                case 'payment-product-detail':
                    $postData = $request->all();
                    $response = new stdClass();
                    $objUserModel = new User();
                    $error = [];
                    if ($postData) {
                        $userId = '';
                        $mainCartData = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }
                        if (isset($postData['productId'])) {
                            $productId = $postData['productId'];
                        }
                        if (isset($postData['selectedVariantId'])) {
                            $selectedVariantId = $postData['selectedVariantId'];
                        }
                        if (isset($postData['quantityId'])) {
                            $quantityId = $postData['quantityId'];
                        }


                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;

                            } else {
                                if ($userId != '') {
                                    $whereForloginToken = $whereForUpdate = [
                                        'rawQuery' => 'id =?',
                                        'bindParams' => [$userId]
                                    ];
                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                                    if ($mytoken == $Userscredentials->login_token) {
                                        $authflag = true;
                                    }
                                }
                            }
                        }
                        if ($authflag) {//LOGIN TOKEN
                            $and = (count(explode('_', $selectedVariantId)) > 1 ?
                                'product_option_variants_combination.variant_ids IN("' . $selectedVariantId . '","' . strrev($selectedVariantId) . '")' :
                                "product_option_variants.variant_id =" . $selectedVariantId);

//                            $where = ['rawQuery' => 'product_option_variants_combination.product_id = ? AND product_option_variants_combination.variant_ids IN("' . $selectedCombination . '","' . strrev($selectedCombination) . '")', 'bindParams' => [$productId]];
                            $where = ['rawQuery' => 'product_option_variants_combination.product_id = ? AND ' . $and, 'bindParams' => [$productId]];

                            $selectedColumn = ['products.*',
                                'productmeta.quantity_discount',
                                'users.email', 'users.name', 'users.last_name', 'users.username', 'users.role',
//                                'usermeta.addressline1','usermeta.addressline2','usermeta.zipcode',
                                'product_option_variants.*',
                                'product_images.*',
                                'product_option_variants_combination.*',
                                'product_option_variant_relation.*',
                                DB::raw('GROUP_CONCAT(
                    CASE
                    WHEN ((SELECT COUNT(pi_id) FROM product_images  WHERE product_images.for_combination_id !="0")!=0)
                    THEN
                        CASE
                            WHEN (product_images.image_type =1 AND (product_images.for_combination_id!=0 OR product_images.for_combination_id!=""))
                            THEN product_images.image_type
                         END
                     ELSE  product_images.image_type
                    END) AS image_types'),
                                DB::raw('GROUP_CONCAT(DISTINCT
                    CASE
                    WHEN ((SELECT COUNT(pi_id) FROM product_images  WHERE product_images.for_combination_id !="0")!=0)
                    THEN
                        CASE
                            WHEN (product_images.image_type =1 AND (product_images.for_combination_id!=0 OR product_images.for_combination_id!=""))
                            THEN product_images.image_url
                         END
                     ELSE  product_images.image_url
                    END) AS image_urls'),

                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_ids) AS variant_id'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data SEPARATOR "____") AS variant_datas')

                            ];
                            $optionVariantDetailsForPopUp = $objOptionVariant->getOptionVariantDetailsForPopup($where, $selectedColumn);
                            if ($optionVariantDetailsForPopUp->in_stock >= $quantityId) {
                                if (isset($optionVariantDetailsForPopUp->variant_ids_combination) && $optionVariantDetailsForPopUp->variant_ids_combination != '') {
                                    $variantData = explode("____", $optionVariantDetailsForPopUp->variant_datas);
                                    $varian = array_flatten(array_map(function ($v) {
                                        return json_decode($v);
                                    }, $variantData));

                                }
                                $finalPrice = $optionVariantDetailsForPopUp->price_total;
                                $combineVarian = array_values(array_filter(array_map(function ($v) use ($varian, &$finalPrice) {
                                    return current(array_filter(array_map(function ($value) use ($v, &$finalPrice) {
                                        if ($v == $value->VID) {
                                            $finalPrice = $finalPrice + $value->PM;
                                            return [$v => $value->PM];
                                        }
                                    }, $varian)));
                                }, explode("_", $optionVariantDetailsForPopUp->variant_ids_combination))));
                                $optionVariantDetailsForPopUp->finalPrice = $finalPrice * $quantityId;

                                $discountedValue = 0;
                                $qtyValue = null;
                                if ($optionVariantDetailsForPopUp->quantity_discount != '') {
//                                    $quantityId = 8;
                                    $quantityDiscount = object_to_array(json_decode($optionVariantDetailsForPopUp->quantity_discount));
                                    $quantities = array_column($quantityDiscount, 'quantity');

//                                $quantities = [3, 2, 8, 10, 6, 25];
                                    $tmpQTY = array_filter(array_map(function ($v) use ($quantityId) {
                                        if ($quantityId >= $v) return $v;
                                    }, $quantities));
                                    sort($tmpQTY);
                                    $finalQTY = end($tmpQTY);

                                    $qtyValue = current(array_values(array_filter(array_map(function ($v) use ($finalQTY) {
                                        if ($v['quantity'] == $finalQTY) return $v;
                                    }, $quantityDiscount))));

                                    $discountedValue = ($qtyValue['type'] == 1) ? $qtyValue['value'] : ($optionVariantDetailsForPopUp->finalPrice * $qtyValue['value'] / 100);
                                    $discountedPrice = $optionVariantDetailsForPopUp->finalPrice - $discountedValue;


                                    if ($discountedPrice <= 0) {
                                        $error[] = 'Discount not allowed';
                                    }
                                    $optionVariantDetailsForPopUp->discountedPrice = $discountedPrice;

//                                    echo $optionVariantDetailsForPopUp->finalPrice . '<br>';
//                                    echo $discountedPrice . '<br>';
//                                    echo '<pre>';
//                                    print_r($qtyValue);
//                                    print_a($tmpQTY);
//                                    print_a(array_column(object_to_array($quantityDiscount), 'quantity'));
                                }
                                $role = ['0' => 0, '1' => 5, '2' => 3];
                                $data = array(
                                    'for_user_id' => $userId,
                                    'order_type' => 'P',
                                    'product_id' => $productId,
                                    'product_details' => json_encode(['product_name' => $optionVariantDetailsForPopUp->product_name, 'image_url' => $optionVariantDetailsForPopUp->image_url]),
                                    'unit_price' => $optionVariantDetailsForPopUp->price_total,
                                    'quantity' => $quantityId,
                                    'discount_by' => array_search($optionVariantDetailsForPopUp->role, $role),
                                    'discount_type' => (isset($qtyValue['type']) && $qtyValue['type'] == 1) ? 'A' : 'P',
                                    'discount_value' => $discountedValue,
                                    'final_price' => (isset($optionVariantDetailsForPopUp->discountedPrice) && $optionVariantDetailsForPopUp->discountedPrice != 0) ? $optionVariantDetailsForPopUp->discountedPrice : $optionVariantDetailsForPopUp->finalPrice,
                                    'order_status' => 'P',
                                    'created_at' => date("Y-m-d H:i:s", time())
                                );

//                                print_a($data);
                            } else {
                                $error[] = 'Selected quantity should be greater than In-Stock';
                            }

                            if (empty($error) && !empty($data)) {
                                $orderVal = $objOrderModel->insertToOrder($data);
                                if ($orderVal) {
                                    $response->code = 200;
                                    $response->message = "SUCCESS";
                                    $response->data = $orderVal;
                                }
                            } else {
                                $response->code = 198;
                                $response->message = "ERROR";
                                $response->data = $error;
                            }
                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;

                //Akash M. Pai
                case "orderHistory"://TODO order history with pagination here
                    $postData = $request->all();
                    $userId = '';
                    if (isset($postData['id'])) {
                        $userId = $postData['id'];
                    }
                    $mytoken = '';
                    $authflag = false;
                    if (isset($postData['api_token'])) {
                        $mytoken = $postData['api_token'];
                        if ($userId != '') {
                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;
                            } else {
                                $whereForloginToken = $whereForUpdate = [
                                    'rawQuery' => 'id =?',
                                    'bindParams' => [$userId]
                                ];
                                $Userscredentials = $objModelUsers->getUsercredsWhere($whereForloginToken);
                                if ($mytoken == $Userscredentials->login_token) {
                                    $authflag = true;
                                }
                            }
                        }
                    }
                    if ($authflag) {//LOGIN TOKEN
                        // NORMAL DATATABLE STARTS HERE//
                        $whereForOrders = ['rawQuery' => 'order_status != ? and for_user_id = ?', 'bindParams' => ['P', $userId]];
//                        $selectedColumn = ['transactions.*'];//, 'orders.order_status', 'orders.order_id'
                        $allOrdersData = json_decode($objModelOrders->getAllOrdersWhere($whereForOrders), true);
                        //NORMAL DATATABLE ENDS//

                        // FILTERING STARTS FROM HERE//
                        $filteringQuery = '';
                        $filteringBindParams = array();
                        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter' && $_REQUEST['action'][0] != 'filter_cancel') {//TODO filtering in orders not done on UI
                            if ($_REQUEST['order_id'] != '') {
                                $filteringQuery[] = "(`orders`.`order_id` = ?)";
                                array_push($filteringBindParams, $_REQUEST['order_id']);
                            }
                            if ($_REQUEST['date_from'] != '' && $_REQUEST['date_to'] != '') {
                                $filteringQuery[] = "(`order`.`added_date` BETWEEN ? AND ?)";
                                array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_from'])));
                                array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_to'])));
                            } else if ($_REQUEST['date_from'] != '' && $_REQUEST['date_to'] == '') {
                                $filteringQuery[] = "(`order`.`added_date` BETWEEN ? AND ?)";
                                array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_from'])));//TODO check date fromat from view and in db
                                array_push($filteringBindParams, strtotime(time()));
                            } else if ($_REQUEST['date_from'] == '' && $_REQUEST['date_to'] != '') {
                                $filteringQuery[] = "(`order`.`added_date` BETWEEN ? AND ?)";
                                array_push($filteringBindParams, strtotime(1000000000));
                                array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_to'])));
                            }
                            if ($_REQUEST['price_from'] != '' && $_REQUEST['price_to'] != '') {
                                $filteringQuery[] = "(`order`.`final_price` BETWEEN ? AND ?)";
                                array_push($filteringBindParams, intval($_REQUEST['price_from']));
                                array_push($filteringBindParams, intval($_REQUEST['price_to']));
                            } else if ($_REQUEST['price_from'] != '' && $_REQUEST['price_to'] == '') {
                                $filteringQuery[] = "(`order`.`final_price` BETWEEN ? AND ?)";
                                array_push($filteringBindParams, intval($_REQUEST['price_from']));
                                array_push($filteringBindParams, intval(100000000));
                            } else if ($_REQUEST['price_from'] == '' && $_REQUEST['price_to'] != '') {
                                $filteringQuery[] = "(`order`.`final_price` BETWEEN ? AND ?)";
                                array_push($filteringBindParams, intval(100000000));
                                array_push($filteringBindParams, intval($_REQUEST['price_to']));
                            }
                            // Filter Implode //
                            $implodedWhere = ['whereRaw' => 1];
                            if (!empty($filteringQuery)) {
                                $implodedWhere['whereRaw'] = implode(' AND ', array_map(function ($filterValues) {
                                    return $filterValues;
                                }, $filteringQuery));
                                $implodedWhere['bindParams'] = $filteringBindParams;
                            }
                            $iTotalRecords = $iDisplayLength = intval($_REQUEST['length']);
                            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                            $iDisplayStart = intval($_REQUEST['start']);
                            $sEcho = intval($_REQUEST['draw']);
                            $columns = array('orders.order_id');//, 'orders.added_date', 'orders.order_type', 'orders.final_price', 'orders.order_status'
                            $sortingOrder = "";
                            if (isset($_REQUEST['order'])) {
                                $sortingOrder = $columns[$_REQUEST['order'][0]['column']];
                            }
                            if ($implodedWhere['whereRaw'] != 1) {
//                                $whereForOrders = ['rawQuery' => 'order_status != ?', 'bindParams' => ['OP']];
                                $selectedColumn = ['*'];
                                $allOrdersData = json_decode($objModelOrders->getAllOrdersWhereWithLimit($whereForOrders, $implodedWhere, $sortingOrder, $iDisplayLength, $iDisplayStart, $selectedColumn), true);
                            }
                        }
                        // FILTERING ENDS//

                        $returnObj = [
                            'data' => $allOrdersData['data'],
                            'message' => "My orders",
                            'code' => $allOrdersData['code']
                        ];
                        $returnCode = 200;
                    } else {
                        $returnObj = [
                            'data' => null,
                            'message' => "Access Denied",
                            'code' => 401
                        ];
                        $returnCode = 401;
                    }

                    echo json_encode($returnObj, true);
                    break;

                default:
                    break;

            }
        }
    }

    /**
     * @param Request $request
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function orderActionsHandler(Request $request)
    {
        $postData = $request->all();
        $returnObj = array('data' => null, 'message' => "Method not allowed.");
        $dataToReturn = null;
        $objModelUser = new User();
        if ($postData) {
            $userId = '';
            if (isset($postData['id'])) {
                $userId = $postData['id'];
            }
            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];
                if ($userId != '') {
                    if ($mytoken == env("API_TOKEN")) {
                        $authflag = true;
                    } else {
                        $whereForloginToken = $whereForUpdate = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId]
                        ];
                        $Userscredentials = $objModelUser->getUsercredsWhere($whereForloginToken);
                        if ($mytoken == $Userscredentials->login_token) {
                            $authflag = true;
                        }
                    }
                }
            }
            if ($authflag) {
                if (isset($postData['orderId']) && !empty($postData['orderId'])) {
                    $objModelOrders = Orders::getInstance();
                    $method = $postData['method'];
                    switch ($method) {
                        case "cancelRequest"://used to cancel individual product in a transaction, cannot cancel the entire order itself
                            $whereForOrderUpdate = ['rawQuery' => 'order_id = ?', 'bindParams' => [$postData['orderId']]];
                            $dataForOrderUpdate = ['order_status' => 'UC'];//TODO need to append and update order status in json format
                            $updatedResult = json_decode($objModelOrders->updateOrderWhere($dataForOrderUpdate, $whereForOrderUpdate), true);
                            if ($updatedResult['code'] == 200)
                                $returnObj = [
                                    'data' => $postData['orderId'],
                                    'message' => "Order cancel requested",
                                    'code' => 200
                                ];
                            $returnCode = 200;
                            break;

                        default:
                            $returnObj = [
                                'data' => null,
                                'message' => "Service method not specified",
                                'code' => 401
                            ];
                            $returnCode = 401;
                            break;
                    }
                } else {
                    $returnObj = [
                        'data' => null,
                        'message' => "Please select an order.",
                        'code' => 401
                    ];
                    $returnCode = 401;
                }
            } else {
                $returnObj = [
                    'data' => null,
                    'message' => "Access Denied",
                    'code' => 401
                ];
                $returnCode = 401;
            }
        } else {
            $returnObj = [
                'data' => null,
                'message' => "Invalid request",
                'code' => 401
            ];
            $returnCode = 401;
        }
        echo json_encode($returnObj, true);
        die;
    }

    //DONOT WRITE TRANSACTION INSERTION HERE
    public function insertTransactionDetails(Request $request)
    {
        $postData = $request->all();
        $response = new stdClass();
        $objUserModel = new User();
        if ($postData) {
            $userId = '';
            $payresponse = '';
            if (isset($postData['id'])) {
                $userId = $postData['id'];
            }

            if (isset($postData['payresponse'])) {
                $payresponse = json_decode($postData['payresponse']);

            }
            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                } else {
                    if ($userId != '') {
                        $whereForloginToken = $whereForUpdate = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId]
                        ];
                        $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                        if ($mytoken == $Userscredentials->login_token) {
                            $authflag = true;
                        }
                    }
                }
            }
            if ($authflag) {

                $objTransactionModel = Transactions::getInstance();
                $insertTransaction = $objTransactionModel->insertToTransaction(object_to_array($payresponse));
                if ($insertTransaction) {
                    $response->code = 200;
                    $response->message = "Success";
                    $response->data = $insertTransaction;
                } else {
                    $response->code = 400;
                    $response->message = "No Details found.";
                    $response->data = null;
                }

            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
            }
        } else {
            $response->code = 401;
            $response->message = "Invalid request";
            $response->data = null;
        }
        echo json_encode($response, true);

    }


    private function in_array_r($needle, $haystack, $strict = false)
    {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || ($this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

    private function multidimensional_array_search($search_value, $array)
    {
        $mached = array();
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                if (count($value) > 0) {
                    $this->multidimensional_array_search($search_value, $value);
                } else {
                    return array_search($search_value, $array);
                    exit;
                }
            }
        }
    }

    /**
     * @param Request $request
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function orderDetails(Request $request)
    {
        $postData = $request->all();
        $response = new stdClass();
        $objUserModel = new User();
        if ($postData) {
            $userId = '';
            $payresponse = '';
            if (isset($postData['id'])) {
                $userId = $postData['id'];
            }
            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];
                if ($userId != '') {
                    if ($mytoken == env("API_TOKEN")) {
                        $authflag = true;
                    } else {
                        $whereForloginToken = $whereForUpdate = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId]
                        ];
                        $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);
                        if ($mytoken == $Userscredentials->login_token) {
                            $authflag = true;
                        }
                    }
                }
            }
            if ($authflag) {
                if (isset($postData['orderId']) && !is_int($postData)) {
                    $objModelOrders = Orders::getInstance();
                    $whereForOrder = ['rawQuery' => 'order_id = ? and for_user_id = ? and order_status != "P" and tx_type = "P"', 'bindParams' => [$postData['orderId'], $userId]];
                    $selectedColsForOrder = ['orders.*', 'transactions.*', 'payment_methods.pmethod_name'];
                    $resultOrderDetails = json_decode($objModelOrders->getOrderWhere($whereForOrder, $selectedColsForOrder), true);
                    if ($resultOrderDetails['code'] == 200) {
                        $returnObj = [
                            'data' => $resultOrderDetails['data'],
                            'message' => "Order # " . $postData['orderId'],
                            'code' => 200
                        ];
                        $returnCode = 200;
                    } else {
                        $returnObj = [
                            'data' => null,
                            'message' => $resultOrderDetails['message'],
                            'code' => 401
                        ];
                        $returnCode = 401;
                    }

                } else {
                    $returnObj = [
                        'data' => null,
                        'message' => "No such order id exists",
                        'code' => 401
                    ];
                    $returnCode = 401;
                }

            } else {
                $returnObj = [
                    'data' => null,
                    'message' => "Access Denied",
                    'code' => 401
                ];
                $returnCode = 401;
            }
        } else {
            $returnObj = [
                'data' => null,
                'message' => "Invalid request",
                'code' => 401
            ];
            $returnCode = 401;
        }
        echo json_encode($returnObj, true);
        die;
    }
}

?>