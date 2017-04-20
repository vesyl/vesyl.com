<?php

namespace FlashSale\Http\Modules\Admin\Controllers;

use FlashSale\Http\Controllers\Controller;
use FlashSale\Http\Modules\Admin\Models\banners;
use FlashSale\Http\Modules\Admin\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


/**
 * Class BannerController
 * @package FlashSale\Http\Modules\Admin\Controllers
 */
class BannersController extends Controller
{
    public function addbanners(Request $request)
    {
        $type = "";
        $path = "";
        $imageStatus = "";
        $bannerdata = array();
        $objModelBannersImage = Banners::getInstance();

        if ($request->isMethod('post')) {
            $productImages = $_FILES;
//            $inputData = $request->all()['banner_data'];
            $selectType = $request->all();
            $rules = [
                'selectType' => 'required',
            ];
            $messages['selectType.required'] = 'Please select any type.';
            $validator = Validator::make($selectType, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {

                if ($selectType["selectType"] == "type1") {
                    $bannerdetails = $request->all()["banner_data"]["type1"];
                    $rules = [
                        'banner_headertype1' => 'required',
                        'banner_captiontype1' => 'required',
                        'mainimagetype1' => 'required'
                    ];
                    $messages['banner_headertype1.required'] = 'banner header is required.';
                    $messages['banner_captiontype1.required'] = 'banner caption is required.';
                    $messages['mainimagetype1.required'] = 'image is required.';
                    $validator = Validator::make($bannerdetails, $rules, $messages);
                    if ($validator->fails()) {
                        return Redirect::back()
                            ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
                            ->withErrors($validator)
                            ->withInput();
                    }

                    $type = "type1";
                    $path = $productImages["banner_data"]['tmp_name']['type1']["mainimagetype1"];
                    $bannerdata["banner_type"] = "DivOverBanner";
                    $bannerdata["banner_for_ids"] = json_encode(["home" => 0]);
                    $header = $bannerdetails["banner_headertype1"];
                    $caption = $bannerdetails["banner_captiontype1"];
                    $arr = array('header' => $header, 'caption' => $caption);
                    $bannerdata["banner_data"] = json_encode($arr);
                }

                if ($selectType["selectType"] == "type2") {
                    $bannerdetails = $request->all()["banner_data"]["type2"];
                    $rules = [
                        'banner_headertype2' => 'required',
                        'banner_captiontype2' => 'required',
                        'mainimagetype2' => 'required',
                        'for_home' => 'required_without:for_categories',
                        'for_categories' => 'required_without:for_home',
                    ];
                    $messages['banner_headertype2.required'] = 'banner header is required.';
                    $messages['banner_captiontype2.required'] = 'banner caption is required.';
                    $messages['mainimagetype2.required'] = 'image is required.';
                    $messages['for_home.required'] = 'select atleast one checkbox.';
                    $messages['for_categories.required'] = 'select atleast one checkbox.';
                    $validator = Validator::make($bannerdetails, $rules, $messages);
                    if ($validator->fails()) {
                        return Redirect::back()
                            ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
                            ->withErrors($validator)
                            ->withInput();
                    }

                    $homearray = null;
                    $banner_cat = null;
                    $type = "type2";
                    $path = $productImages["banner_data"]['tmp_name']['type2']["mainimagetype2"];
                    if (isset($bannerdetails["for_home"])) {
                        $homearray = 0;
                        $where = [
                            'rawQuery' => 'banner_type = ?',
                            'bindParams' => ["DivSideToBanner"]
                        ];
                        $update['banner_status'] = "I";
                        $objModelBannersImage->udpateHomeBanner($update, $where);
                    }
                    if (isset($bannerdetails["for_categories"])) {
                        $banner_cat = array_keys($bannerdetails["for_categories"]);
                    }
                    $arr = array('home' => $homearray, 'cats' => $banner_cat);
                    $bannerdata["banner_for_ids"] = json_encode($arr);
                    $bannerdata["banner_status"] = "A";
                    $bannerdata["banner_type"] = "DivSideToBanner";
                    $header = $bannerdetails["banner_headertype2"];
                    $caption = $bannerdetails["banner_captiontype2"];
                    $arr = array('header' => $header, 'caption' => $caption);
                    $bannerdata["banner_data"] = json_encode($arr);
                }

                if ($selectType["selectType"] == "type3") {
                    $bannerdetails = $request->all()["banner_data"]["type3"];
                    $rules = [
                        'banner_headertype3' => 'required',
                        'banner_captiontype3' => 'required',
                        'mainimagetype3' => 'required'
                    ];
                    $messages['banner_headertype3.required'] = 'banner header is required.';
                    $messages['banner_captiontype3.required'] = 'banner caption is required.';
                    $messages['mainimagetype3.required'] = 'image is required.';
                    $validator = Validator::make($bannerdetails, $rules, $messages);
                    if ($validator->fails()) {
                        return Redirect::back()
                            ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
                            ->withErrors($validator)
                            ->withInput();
                    }
                    $type = "type3";
                    $path = $productImages["banner_data"]['tmp_name']['type3']["mainimagetype3"];
                    $bannerdata["banner_type"] = "Slider";
                    $bannerdata["banner_for_ids"] = json_encode(["home" => 0]);
                    $header = $bannerdetails["banner_headertype3"];
                    $caption = $bannerdetails["banner_captiontype3"];
                    $arr = array('header' => $header, 'caption' => $caption);
                    $bannerdata["banner_data"] = json_encode($arr);
                }

                $count = $objModelBannersImage->addBanners($bannerdata);
                $count = json_decode($count, true);

                if ($count["code"] == 200) {
                    $mainImageURL = uploadImageToStoragePath($path, "banner/" . $type . "/" . $count["data"], 'banner' . '.jpg', 724, 1024);
                }

            }

        }
        $objModelCategory = ProductCategory::getInstance();
        $whereForCat = [
            'rawQuery' => 'category_status =?',
            'bindParams' => [1]
        ];
        $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);

        return view('Admin/Views/banners/Banners', ['allCategories' => $allCategories]);
    }

    public function deactivatebanners()
    {

    }


}
