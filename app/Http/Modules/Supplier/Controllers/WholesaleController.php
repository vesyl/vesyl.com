<?php
namespace FlashSale\Http\Modules\Supplier\Controllers;


use FlashSale\Http\Modules\Supplier\Models\Campaigns;
use FlashSale\Http\Modules\Supplier\Models\ProductCategory;
use FlashSale\Http\Modules\Supplier\Models\Products;
use FlashSaleApi\Http\Models\ProductCategories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use PDO;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Curl\CurlRequestHandler;
use stdclass;

use Yajra\Datatables\Datatables;

class WholesaleController extends Controller
{

    /**
     * Add Wholesale Campaign For Buyers By Suppliers
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @since 29-02-2016
     * @author Vini Dubey <vinidubey@globussoft.com>
     */
    public function addWholesale(Request $request)
    {

        $objProductCategory = ProductCategory::getInstance();
        $objCampaignModel = Campaigns::getInstance();
        $objProductModel = Products::getInstance();
        $supplierId = Session::get('fs_supplier')['id'];
        $where = ['rawQuery' => 'added_by = ? AND product_type = ?', 'bindParams' => [$supplierId, 1]];
        $selectedColumn = ['product_name', 'product_id'];
        $getProducts = $objProductModel->getAllSupplierProducts($where, $selectedColumn);

        if ($request->isMethod('post')) {
            $rules = array(
                'campaign_name' => 'required|unique:campaigns',
                'percentagediscount' => 'required|min:40|numeric',
                'wholesale_image' => 'image|required',
                'productcategories' => 'required',
                'product' => 'required'

            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            if (Input::hasFile('wholesale_image')) {
                $filePath = uploadImageToStoragePath(Input::file('wholesale_image'), 'wholesale', 'wholesale_' . $supplierId . '_' . time() . ".jpg");
                $data['campaign_banner'] = $filePath;
            }
            $postData = $request->all();
            $data['campaign_name'] = $postData['campaign_name'];
            $data['campaign_type'] = 3;
            $data['discount_type'] = '2';
            $data['discount_value'] = $postData['percentagediscount'];
            $postData['availablefromdate'] = null;
            $postData['availableuptodate'] = null;
            $categ = $postData['productcategories'];
            if (isset($postData['productsubcategories'])) {
                $subcat = $postData['productsubcategories'];
            } else {
                $subcat = [];
            }
            $tmp = [];
            foreach ($categ as $index => $item) {
                $tmp[$item] = array_values(array_filter(array_map(function ($cat) use ($item) {
                    if (explode('_', $cat)[0] == $item) {
                        return explode('_', $cat)[1];
                    }
                }, $subcat)));
            }
            $data['by_user_id'] = $supplierId;
            $product = $postData['product'];
            $data['for_category_ids'] =  json_encode($tmp);
            $data['for_product_ids'] = implode(",", $product);
            $campaigns = 'Wholesale';
            $campaign = $objCampaignModel->addFlashsale($data);
            if ($campaign) {
                return Redirect::back()->with(['status' => 'success', 'msg' => $campaigns . ' ' . 'Campaign Added Successfully.']);
            } else {
                return Redirect::back()->with(['status' => 'error', 'msg' => 'Some Error try again.']);
            }
        }
        return view('Supplier/Views/wholesale/addWholesale', ['Products' => $getProducts]);
    }

    public function manageWholesale()
    {

        return view('Supplier/Views/wholesale/manageWholesale');
    }

    public function wholesaleAjaxHandler(Request $request, $method)
    {

        $inputData = $request->input();
        $objCategoryModel = ProductCategory::getInstance();
        $objCampaignModel = Campaigns::getInstance();
        $objProductModel = Products::getInstance();
        $supplierId = Session::get('fs_supplier')['id'];
        switch ($method) {

            case 'manageWholesale':
                //   Modify code for filter //
                $iTotalRecords = $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                $columns = array('campaigns.campaign_id', 'campaigns.campaign_name', 'campaigns.campaign_type', 'users.username', 'campaigns.discount_value', 'category', 'product', 'campaigns.campaign_status');
                $sortingOrder = "";
                if (isset($_REQUEST['order'])) {
                    $sortingOrder = $columns[$_REQUEST['order'][0]['column']];
                }
                if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {

                    if ($_REQUEST['customActionValue'] != '' && !empty($_REQUEST['wholesaleId'])) {

                        $statusData['campaign_status'] = $_REQUEST['customActionValue'];
                        $whereForStatusUpdate = ['rawQuery' => 'campaign_id IN (' . implode(',', $_REQUEST['wholesaleId']) . ')'];
                        $updateResult = $objCampaignModel->updateFlashsaleStatus($statusData, $whereForStatusUpdate);
                        if ($updateResult) {
                            //NOTIFICATION TO USER FOR ORDER STATUS CHANGE
                            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                            $records["customActionMessage"] = "Group action successfully has been completed."; // pass custom message(useful for getting status of group actions)
                        }
                    }
                }
                // End Modify code for filter//

                //FOR NORMAL DATATABLE LOAD OPERATION//
                $where = ['rawQuery' => 'campaign_type = ? AND by_user_id = ?', 'bindParams' => [3, $supplierId]];
                $selectedColumn = ['campaigns.*', 'users.username'];
                $dailyspecialInfo = $objCampaignModel->getAllFlashsaleDetails($where, $selectedColumn);
                foreach ($dailyspecialInfo as $flashkey => $flashval) {
                    $productIds = $flashval->for_product_ids;
                    $whereProd = ['rawQuery' => 'product_id IN(' . $productIds . ')'];
                    $selectedColumn = [DB::raw('GROUP_CONCAT(DISTINCT product_name) AS product_name'), DB::raw('GROUP_CONCAT(DISTINCT product_id) AS product_id')];
                    $getproduct = $objProductModel->getProductNameById($whereProd, $selectedColumn);
                    foreach ($getproduct as $prodkey => $prodval) {
                        $dailyspecialInfo[$flashkey]->product = $prodval->product_name;
                    }
                }
                $flash = json_decode(json_encode($dailyspecialInfo), true);
                $flashDetail = new Collection();
                foreach ($flash as $mainflashkey => $mainflashval) {
                    $flashDetail->push([
                        'checkbox' => '<input type="checkbox" name="id[]" value="' . $mainflashval['campaign_id'] . '">',
                        'campaign_id' => $mainflashval['campaign_id'],
                        'campaign_name' => $mainflashval['campaign_name'],
                        'campaign_type' => ($mainflashval['campaign_type'] = 'Wholesale'),
                        'username' => $mainflashval['username'],
                        'discount_value' => $mainflashval['discount_value'] . '%',
                        'product' => count(explode(",", $mainflashval['product'])),
                        'campaign_status' => $mainflashval['campaign_status'],
                        'action' => '',
                        'supplierId' => Session::get('fs_supplier')['id']

                    ]);
                }
                // END FOR NORMAL DATATABLE LOAD OPERATION //

                //FILTERING START FROM HERE //
                $filteringRules = '';
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter' && $_REQUEST['action'][0] != 'filter_cancel') {

                    if ($_REQUEST['campaign_id'] != '') {
                        $filteringRules[] = "(`campaigns`.`campaign_id` = " . $_REQUEST['campaign_id'] . " )";
                    }
                    if ($_REQUEST['campaign_name'] != '') {
                        $filteringRules[] = "(`campaigns`.`campaign_name` LIKE '%" . $_REQUEST['campaign_name'] . "%' )";
                    }
                    if ($_REQUEST['username'] != '') {
                        $filteringRules[] = "(`users`.`username` LIKE '%" . $_REQUEST['username'] . "%' )";
                    }
                    if ($_REQUEST['discount_value_from'] != '' && $_REQUEST['discount_value_to'] != '') {
                        $filteringRules[] = "(`campaigns`.`discount_value` BETWEEN " . intval($_REQUEST['discount_value_from']) . " AND " . intval($_REQUEST['discount_value_to']) . "  )";
                    }
                    if ($_REQUEST['campaign_status'] != '') {
                        $filteringRules[] = "(`campaigns`.`campaign_status` = " . $_REQUEST['campaign_status'] . " )";
                    }

                    // FOR CAMPAIGN FILTER //
                    $implodedWhere = '';
                    if (!empty($filteringRules)) {
                        $implodedWhere = implode(' AND ', array_map(function ($filterValues) {
                            return $filterValues;
                        }, $filteringRules));
                    }
                    $where = ['rawQuery' => 'campaign_type = ?', 'bindParams' => [3]];
                    $selectedColumn = ['campaigns.*', 'users.username'];
                    $MainFlashInfo = $objCampaignModel->getAllFlashDetail($where, $implodedWhere, $sortingOrder, $iDisplayLength, $iDisplayStart, $selectedColumn);
                    foreach ($MainFlashInfo as $flashkey => $flashval) {
                        $productIds = $flashval->for_product_ids;
                        $whereProd = ['rawQuery' => 'product_id IN(' . $productIds . ')'];
                        $selectedColumn = [DB::raw('GROUP_CONCAT(DISTINCT product_name) AS product_name'), DB::raw('GROUP_CONCAT(DISTINCT product_id) AS product_id')];
                        $getproduct = $objProductModel->getProductNameById($whereProd, $selectedColumn);
                        foreach ($getproduct as $prodkey => $prodval) {
                            $MainFlashInfo[$flashkey]->product = $prodval->product_name;
                        }
                    }

                    // FOR DISPLAYING DATA TO DATATABLE //
                    $flash = json_decode(json_encode($MainFlashInfo), true);
                    $flashDetail = new Collection();
                    foreach ($flash as $mainflashkey => $mainflashval) {
                        $flashDetail->push([
                            'checkbox' => '<input type="checkbox" name="id[]" value="' . $mainflashval['campaign_id'] . '">',
                            'campaign_id' => $mainflashval['campaign_id'],
                            'campaign_name' => $mainflashval['campaign_name'],
                            'campaign_type' => $mainflashval['campaign_type'] = 'Wholesale',
                            'username' => $mainflashval['username'],
                            'discount_value' => $mainflashval['discount_value'] . '%',
//                            'category' => $mainflashval['category'],
                            'product' => count(explode(",", $mainflashval['product'])),
                            'campaign_status' => $mainflashval['campaign_status'],
                            'action' => '',
                            'supplierId' => Session::get('fs_supplier')['id']

                        ]);
                    }
                }
                //FILTERING ENDS HERE //
                $status_list = array(
                    0  =>array("pending"=>"Pending"),
                    1 => array("success" => "Success"),
                    2 => array("primary" => "InActive"),
                    3 => array("warning" => "Rejected"),
                    4 => array("danger" => "Deleted"),
                    5 => array("danger" => "Finished"),
                );
                // CONTINUE FOR NORMAL DATATABLE LOAD //
                return Datatables::of($flashDetail, $status_list)
                    ->addColumn('action', function ($flashDetail) {
                        return '<span class="tooltips" title="Edit Wholesale Details." data-placement="top"> <a href="/supplier/edit-wholesale/' . $flashDetail['campaign_id'] . '" class="btn btn-sm grey-cascade" style="margin-left: 10%;">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            </span>';
                    })
                    ->addColumn('campaign_status', function ($flashDetail) use ($status_list) {
                        return '<span class="label label-sm label-' . (key($status_list[$flashDetail['campaign_status']])) . '">' . (current($status_list[$flashDetail['campaign_status']])) . '</span>';
                    })
                    ->removeColumn('for_shop_id')
                    ->removeColumn('available_from')
                    ->removeColumn('available_upto')
                    ->removeColumn('campaign_banner')
                    ->make();
                // END FOR CONTNUATION OF NORMAL DATATABLE LOAD //
                $records["recordsFiltered"] = $iTotalFilteredRecords;
                echo json_encode($records);

                break;
            default:
                break;
        }
    }

    /**
     * Edit Wholesale Campaign For Buyers
     * @param Request $request
     * @param $wid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @since 29-02-2016
     * @author Vini Dubey <vinidubey@globussoft.com>
     */
    public function editWholesale(Request $request, $wid)
    {

        $objProductCategory = ProductCategory::getInstance();
        $objCampaignModel = Campaigns::getInstance();
        $objProductModel = Products::getInstance();
        $supplierId = Session::get('fs_supplier')['id'];

        if ($request->isMethod('post')) {
            $rules = array(
                'campaign_name' => 'unique:campaigns,campaign_name,' . $wid . ',campaign_id',
                'wholesale_image' => 'image',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            if (Input::hasFile('wholesale_image')) {
                $filePath = uploadImageToStoragePath(Input::file('wholesale_image'), 'wholesale', 'wholesale_' . $supplierId . '_' . time() . ".jpg");
                $data['campaign_banner'] = $filePath;
            }
            $postData = $request->all();
            $data['campaign_name'] = $postData['campaign_name'];
            $data['discount_type'] = '2';
            $data['discount_value'] = $postData['percentagediscount'];
            $categ = $postData['productcategories'];
            if (isset($postData['productsubcategories'])) {
                $subcat = $postData['productsubcategories'];
            } else {
                $subcat = [];
            }
            $tmp = [];
            foreach ($categ as $index => $item) {
                $tmp[$item] = array_values(array_filter(array_map(function ($cat) use ($item) {
                    if (explode('_', $cat)[0] == $item) {
                        return explode('_', $cat)[1];
                    }
                }, $subcat)));
            }
            $data['for_category_ids'] = json_encode($tmp);
            $data['by_user_id'] = $supplierId;
//            $data['for_product_ids'] = implode(",", $postData['product']);
            $where = ['rawQuery' => 'campaign_id = ?', 'bindParams' => [$wid]];
            $campaigns = 'Wholesale';
            $campaignUpdate = $objCampaignModel->updateFlashsaleStatus($data, $where);
            if ($campaignUpdate) {
                if (isset($filePath))
                    deleteImageFromStoragePath($postData['oldImage']);
                return Redirect::back()->with(['status' => 'success', 'msg' => $campaigns . ' ' . 'Updated Successfully.']);
            } else {
                return Redirect::back()->with(['status' => 'error', 'msg' => $campaigns . ' ' . 'Some Error try again.']);
            }
        }

        $where = ['rawQuery' => 'campaign_id = ?  AND by_user_id = ?', 'bindParams' => [$wid, $supplierId]];
        $selectedColumn = ['campaigns.*', 'users.username'];
        $wholesaleInfo = $objCampaignModel->getAllFlashsaleDetails($where, $selectedColumn);
        if ((isset($wholesaleInfo) && (!empty($wholesaleInfo)))) {
            foreach ($wholesaleInfo as $flashkey => $flashval) {
                $categoryIds = json_decode($flashval->for_category_ids, true);
                $categoryMerg = array_merge(array_keys($categoryIds));
                $categoryMergee = array_merge(array_flatten($categoryIds));
                $categoryMerge = array_merge(array_keys($categoryIds), array_flatten($categoryIds));
                $where = ['rawQuery' => 'category_id IN(' . implode(",", $categoryMerge) . ')'];
                $selectedColumn = [DB::raw('GROUP_CONCAT(DISTINCT category_name) AS category_name'), DB::raw('GROUP_CONCAT(DISTINCT category_id) AS category_id')];
                $getcategory = $objProductCategory->getCategoryNameById($where, $selectedColumn);
                foreach ($getcategory as $catkey => $catval) {
                    $wholesaleInfo[$flashkey]->category = $catval->category_name;
                    $wholesaleInfo[$flashkey]->category_ids = $catval->category_id;
                }
                $whereProduct = ['rawQuery' => 'product_id IN(' . $flashval->for_product_ids . ')'];
                $selectedColumn = [DB::raw('GROUP_CONCAT(DISTINCT product_name) AS product_name'), DB::raw('GROUP_CONCAT(DISTINCT product_id) AS product_id')];

                $getproduct = $objProductModel->getProductNameById($whereProduct, $selectedColumn);

                foreach ($getproduct as $prodkey => $prodval) {
                    $wholesaleInfo[$flashkey]->product_name = $prodval->product_name;
                    $wholesaleInfo[$flashkey]->product_id = $prodval->product_id;
                }
            }

            $where = ['rawQuery' => 'category_status = ? AND parent_category_id = ?', 'bindParams' => [1, 0]];
            $selectedColumn = ['category_id', 'category_name', 'category_status', 'for_shop_id'];
            $allactivecategories = $objProductCategory->getAllMainCategories($where, $selectedColumn);
            $where = ['rawQuery' => 'category_status = ?', 'bindParams' => [1]];
            $selectedColumn = ['product_categories.*', DB::raw('GROUP_CONCAT(category_id)AS main_category_id'),
                DB::raw('GROUP_CONCAT(category_name)AS main_category_name')];
            $allActiveSubcategories = $objProductCategory->getSubCategoriesForMaincategory($where, $selectedColumn);

            $mainCategory = array_filter(array_map(function ($category) {
                if ($category->parent_category_id == 0)
                    return $category;
            }, $allActiveSubcategories))[0];

            $finalCatData = [];
            foreach (explode(',', $mainCategory->main_category_id) as $index => $mainCatID) {
                foreach ($allActiveSubcategories as $subCatKey => $allActiveSubcategory) {
                    if ($allActiveSubcategory->parent_category_id == $mainCatID) {
                        $allActiveSubcategory->main_cat_name = explode(',', $mainCategory->main_category_name)[$index];
                        $finalCatData[$mainCatID] = $allActiveSubcategory;
                    }
                }
            }
            $where = ['rawQuery' => 'added_by = ? AND product_type = ?', 'bindParams' => [$supplierId, 1]];
            $selectedColumn = ['product_id', 'product_name'];

            $allproducts = $objProductModel->getAllSupplierProducts($where, $selectedColumn);

            return view('Supplier/Views/wholesale/editWholesale', ['wholesaleDetails' => $wholesaleInfo[0], 'activeCategory' => $allactivecategories, 'allProducts' => $allproducts, 'allcategories' => $finalCatData]);

        } else {
            return view('Supplier/Views/wholesale/editWholesale');
        }

    }


}