<?php

namespace FlashSale\Http\Modules\Supplier\Controllers;

use FlashSale\Http\Modules\Supplier\Models\User;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Validator;
use Redirect;
use View;

use Illuminate\Support\Facades\Session;

use FlashSale\Http\Modules\Supplier\Models\ProductFeatures;
use FlashSale\Http\Modules\Supplier\Models\ProductFeatureVariants;
use FlashSale\Http\Modules\Supplier\Models\ProductCategory;

class FeaturesController extends Controller
{
    public function manageFeatures()
    {
        $userId = Session::get('fs_supplier')['id'];
        $objModelFeatures = ProductFeatures::getInstance();
        $userModel = User::getInstance();
        $where['rawQuery'] = 1;
        $featuresData = $objModelFeatures->getAllFeaturesWhere($where);
        $admindata = $userModel->getAdmindata();
        $dataForView = json_decode($featuresData, true);
        $dataForView['added_by'] = $userId;
//        dd(  $dataForView['added_by']);
        $errMsg = null;
        if ($dataForView['code'] != 200) {
            $errMsg = $dataForView['message'];
        }
        return view('Supplier/Views/features/manageFeatures', ['allFeatures' => json_decode($featuresData, true), 'errMsg' => $errMsg, 'data' => $dataForView['added_by']]);
    }

    public function addFeatureGroup(Request $request)
    {
        $userId = Session::get('fs_supplier')['id'];
        $objModelFeatures = ProductFeatures::getInstance();
        $objModelCategory = ProductCategory::getInstance();
        $whereForCat = [
            'rawQuery' => 'category_status =?',
            'bindParams' => [1]
        ];
        $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);
        if ($request->isMethod('post')) {
//            dd($request->all());
//            echo "<pre>"; print_r($request->input()); die;
            $rulesAddFG = [
                'feature_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255|unique:product_features,feature_name,null,feature_id,parent_id,0,group_flag,1',
                'full_description' => 'max:255',
                'for_categories' => 'required'//$for_categoriesReqFlag
            ];
            $messagesAddFG = ['feature_name.required' => 'Please enter a name',
                'feature_name.alpha_num' => 'Please enter a valid name',
                'feature_name.unique' => 'Feature group already exists',
                'full_description.max' => 'Description should not exceed 255 characters',
                'for_categories.required' => 'Please select atleast one category'
            ];
//            $this->validate($request, $rulesAddFG, $messagesAddFG);
            $validator = Validator::make($request->all(), $rulesAddFG, $messagesAddFG);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["code" => '400', 'message' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $dataAddFeature = array();
                $dataAddFeature['feature_name'] = $request->input('feature_name');
                $dataAddFeature['full_description'] = $request->input('full_description');
                $selectedCategories = $request->input('for_categories');
                $dataAddFeature['for_categories'] = implode(',', array_keys($selectedCategories));
                $dataAddFeature['added_by'] = $userId;
                $dataAddFeature['status'] = 1;
                $dataAddFeature['parent_id'] = 0;
                $dataAddFeature['full_description'] = $request->input('full_description');
                $dataAddFeature['display_on_product'] = 0;
                if ($request->input('display_on_product') == "on") {
                    $dataAddFeature['display_on_product'] = 1;
                }
                $dataAddFeature['display_on_catalog'] = 0;
                if ($request->input('display_on_catalog') == "on") {
                    $dataAddFeature['display_on_catalog'] = 1;
                }
                $dataAddFeature['group_flag'] = 1;
//            echo "<pre>"; print_r($dataAddFeature); die;
                $inserted = json_decode($objModelFeatures->addFeature($dataAddFeature), true);
                if ($inserted['code'] == 200) {
                    return Redirect::back()->with(['code' => '200', 'message' => 'New feature ' . $request->input('feature_name') . ' has been added.']);
                } else {
                    return Redirect::back()->with(['code' => '400', 'message' => 'Something went wrong, please reload the page and try again.']);
                }
            }
        }
        return view('Supplier/Views/features/addFeatureGroup', ['code' => '', 'allCategories' => $allCategories]); //, ['successMsg' => $successMsg, 'errMsg' => $errMsg]
    }

    public function editFeatureGroup(Request $request, $featureId)
    {
        $userId = Session::get('fs_supplier')['id'];
        $objModelFeatures = ProductFeatures::getInstance();
        $whereForFeatureGroup = [
            'rawQuery' => 'feature_id = ? and added_by = ?',
            'bindParams' => [$featureId, $userId]
        ];
        $featureDetails = $objModelFeatures->getFeatureWhere($whereForFeatureGroup);
//        echo "<pre>"; print_r(json_decode($featureDetails, true)); die;
        $objModelCategory = ProductCategory::getInstance();
        $whereForCat = [
            'rawQuery' => 'category_status =?',
            'bindParams' => [1]
        ];
        $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);

        if ($request->isMethod('post')) {

//            echo "<pre>"; print_r($request->input()); die;
            $rulesEditFG = [
                'feature_name' => "required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255|unique:product_features,feature_name,$featureId,feature_id,parent_id,0,group_flag,1",
                'full_description' => 'max:255',
                'for_categories' => 'required'//$for_categoriesReqFlag
            ];
            $messagesEditFG = ['feature_name.required' => 'Please enter a name',
                'feature_name.alpha_num' => 'Please enter a valid name',
                'feature_name.unique' => 'Feature group already exists',
                'full_description.max' => 'Description should not exceed 255 characters',
                'for_categories.required' => 'Please select atleast one category'
            ];
//            $this->validate($request, $rulesEditFG, $messagesEditFG);
            $validator = Validator::make($request->all(), $rulesEditFG, $messagesEditFG);
//            dd($validator);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["code" => '400', 'message' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $whereForFeatureUpdate = [
                    'rawQuery' => 'feature_id =?',
                    'bindParams' => [$featureId]
                ];
                $dataUpdateFeature = array();
                $dataUpdateFeature['feature_name'] = $request->input('feature_name');
//                $dataUpdateFeature['shop_id'] = $userId;
//                $dataUpdateFeature['group_flag'] = 1;
//                $dataUpdateFeature['feature_type'] = 1;//todo
                $selectedCategories = $request->input('for_categories');
                $dataUpdateFeature['for_categories'] = implode(',', array_keys($selectedCategories));
//                $dataUpdateFeature['parent_id'] = $userId;
                $dataUpdateFeature['full_description'] = $request->input('full_description');
                $dataUpdateFeature['display_on_product'] = 0;
                if ($request->input('display_on_product') != null) {
                    $dataUpdateFeature['display_on_product'] = 1;
                }
                $dataUpdateFeature['display_on_catalog'] = 0;
                if ($request->input('display_on_catalog') != null) {
                    $dataUpdateFeature['display_on_catalog'] = 1;

                    $updated = $objModelFeatures->updateFeatureWhere($dataUpdateFeature, $whereForFeatureUpdate);
                    return Redirect::back()->with(json_decode($updated, true));

                }
            }
            return view('Supplier/Views/features/editFeatureGroup', ['code' => '', 'allCategories' => $allCategories, 'featureDetails' => json_decode($featureDetails, true)]); //, ['successMsg' => $successMsg, 'errMsg' => $errMsg]

        }

        return view("Supplier/Views/features/editFeatureGroup", ['code' => '', 'featureDetails' => $featureDetails, 'allCategories' => $allCategories, 'featureDetails' => json_decode($featureDetails, true)]);
    }


    public function addFeature(Request $request)
    {
        $userId = Session::get('fs_supplier')['id'];
        $objModelFeatures = ProductFeatures::getInstance();
        $objFeatureVariantsModel = ProductFeatureVariants::getInstance();
        $whereForFeature = [
            'rawQuery' => 'group_flag =? and status = ?',
            'bindParams' => [1, 1]
        ];
        $allFeatureGroups = $objModelFeatures->getAllFeaturesWhere($whereForFeature);
        $objModelCategory = ProductCategory::getInstance();
        $whereForCat = [
            'rawQuery' => 'category_status =?',
            'bindParams' => [1]
        ];

        $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);
        if ($request->isMethod('post')) {
//            echo "<pre>"; print_r($request->input()); die;
            $dataAddFeature = array();
            $dataAddFeature['parent_id'] = 0;
            if (null !== $request->input('parent_id')) {
                $dataAddFeature['parent_id'] = $request->input('parent_id');
            }
            $rulesAddFeature = [
                'feature_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255|unique:product_features,feature_name,null,feature_id,parent_id,' . $dataAddFeature['parent_id'] . ',group_flag,0',
                'full_description' => 'max:255',
                'feature_type' => 'required',
                'for_categories' => 'required'//$for_categoriesReqFlag
            ];
            $messagesAddFeature = ['feature_name.required' => 'Please enter a name',
                'feature_name.regex' => 'Name can contain alphanumeric characters and spaces only',
                'feature_name.max' => 'Name is too long to use. 255 characters max.',
                'feature_name.unique' => 'Similar Feature already exists',
                'full_description.max' => 'Description should not exceed 255 characters',
                'feature_type.required' => 'Please select a feature type',
                'for_categories.required' => 'Please select atleast one category'
            ];
            $featureVariants = $request->input('feature_variant')['name'];
            $featureVariantsDesc = $request->input('feature_variant')['description'];
//            echo '<pre>'; print_r($featureVariants); die;
            foreach ($featureVariants as $keyFV => $valueFV) {
                //TODO NEED TO INCLUDE VALIDATION TO VARIANTS BASED ON FEATURE TYPE
                if ($valueFV == "") {
                    unset($featureVariants[$keyFV]);
                }
                if (array_key_exists($keyFV, $featureVariants)) {
                    $rulesAddFeature['feature_variant.name.' . $keyFV] = 'regex:/(^[A-Za-z0-9 ]+$)+/|max:255';
                    $messagesAddFeature['feature_variant.name.' . $keyFV . '.regex'] = 'Name can contain alphanumeric characters and spaces only';
                    $messagesAddFeature['feature_variant.name.' . $keyFV . '.max'] = 'Name is too long to use. 255 characters max.';
                }
            }
//            $this->validate($request, $rulesAddFeature, $messagesAddFeature);
            $validator = Validator::make($request->all(), $rulesAddFeature, $messagesAddFeature);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["code" => '400', 'message' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {
                if ($request->input('feature_type') != 0 && count($featureVariants) < 2) {
                    return Redirect::back()
                        ->with(["code" => '400', 'message' => 'Please add atleast two variants for this feature type..'])
                        ->withErrors($validator)
                        ->withInput();
                }
                $dataAddFeature['feature_name'] = $request->input('feature_name');
                $dataAddFeature['added_by'] = $userId;
//                $dataAddFeature['parent_id'] = $request->input('feature_id');
//                $dataAddFeature['feature_id'] = $request->input('feature_id');
                $dataAddFeature['group_flag'] = 0;
                $dataAddFeature['full_description'] = $request->input('full_description');
                $dataAddFeature['feature_type'] = $request->input('feature_type');
                $selectedCategories = $request->input('for_categories');
                $dataAddFeature['for_categories'] = implode(',', array_keys($selectedCategories));
                $dataAddFeature['display_on_product'] = 0;
                if ($request->input('display_on_product') == "on") {
                    $dataAddFeature['display_on_product'] = 1;
                }
                $dataAddFeature['display_on_catalog'] = 0;
                if ($request->input('display_on_catalog') == "on") {
                    $dataAddFeature['display_on_catalog'] = 1;
                }
                $dataAddFeature['status'] = 1;

                $inserted = json_decode($objModelFeatures->addFeature($dataAddFeature), true);
                if ($inserted['code'] == 200) {
                    //CODE TO INSERT VARIANTS HERE
                    if ($dataAddFeature['feature_type'] != 0) {
                        foreach ($featureVariants as $keyFV => $valueFV) {
                            $dataAddFV = array('variant_name' => $valueFV, 'description' => $featureVariantsDesc[$keyFV], 'feature_id' => $inserted['data']);
                            $result = $objFeatureVariantsModel->addFeatureVariant($dataAddFV);
                        }
                    }
                    return Redirect::back()->with(['code' => '200', 'message' => 'New feature <b>' . $request->input('feature_name') . '</b> has been added.']);
                } else {
                    return Redirect::back()->with(['code' => '400', 'message' => 'Something went wrong, please reload the page and try again.']);
                }
            }
        }
        return view('Supplier/Views/features/addFeature', ['code' => '', 'featureGroups' => json_decode($allFeatureGroups, true), 'allCategories' => $allCategories]); //, ['successMsg' => $successMsg, 'errMsg' => $errMsg]

    }

    public function editFeature(Request $request, $featureId)
    {
//        dd($featureId);
        $userId = Session::get('fs_supplier')['id'];
        $objModelFeatures = ProductFeatures::getInstance();
        $objModelCategory = ProductCategory::getInstance();
        $objModelFeatureVariants = ProductFeatureVariants::getInstance();

        $whereForFeatureGroup = ['rawQuery' => 'group_flag =? and status = ?', 'bindParams' => [1, 1]];
        $allFeatureGroups = json_decode($objModelFeatures->getAllFeaturesWhere($whereForFeatureGroup), true);
//dd($allFeatureGroups);
        $whereForFeature = ['rawQuery' => 'feature_id = ? and group_flag=? and added_by = ?', 'bindParams' => [$featureId, 0, $userId]];
        $featureDetails = json_decode($objModelFeatures->getFeatureWhere($whereForFeature), true);

        if ($featureDetails['code'] == 200) {


            $whereForCat = ['rawQuery' => 'category_status =?', 'bindParams' => [1]];
            $allCategories = $objModelCategory->getAllCategoriesWhere($whereForCat);

            $whereForFV = ['rawQuery' => 'feature_id = ?', 'bindParams' => [$featureId]];
            $fvData = $objModelFeatureVariants->getAllFeatureVariantsWhere($whereForFV);

            if ($request->isMethod('post')) {
//            echo "<pre>"; print_r($request->input()); die;
                $dataUpdateFeature = array();
                $dataUpdateFeature['parent_id'] = (string)json_decode($featureDetails, true)['data']['parent_id'];
//                dd($dataUpdateFeature);
                if ($request->input('parent_id') != null) {
                    $dataUpdateFeature['parent_id'] = $request->input('parent_id');
                }

                $rulesEditdFeature = [
                    'feature_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255|unique:product_features,feature_name,' . $featureId . ',feature_id,parent_id,' . $dataUpdateFeature['parent_id'] . ',group_flag,0',
                    'full_description' => 'max:255',
                    'feature_type' => 'required',
                    'for_categories' => 'required'//$for_categoriesReqFlag
                ];

                $messagesEditFeature = ['feature_name.required' => 'Please enter a name',
                    'feature_name.alpha_num' => 'Please enter a valid name',
                    'full_description.max' => 'Description should not exceed 255 characters',
                    'feature_type.required' => 'Please select a feature type',
                    'for_categories.required' => 'Please select atleast one category'
                ];

                $featureVariants = $request->input('feature_variant')['name'];
                $featureVariantsDesc = $request->input('feature_variant')['description'];
                $featureVariantIds = $request->input('feature_variant')['variant_id'];
//            echo '<pre>'; print_r($featureVariantIds); die;
                foreach ($featureVariants as $keyFV => $valueFV) {
                    //NEED MORE VALIDATION HERE
                    $rulesEditdFeature['feature_variant.name.' . $keyFV] = 'regex:/(^[A-Za-z0-9 ]+$)+/|max:255|unique:product_feature_variants,variant_name,' . (isset($featureVariantIds[$keyFV]) ? $featureVariantIds[$keyFV] : 'NULL') . ',variant_id,feature_id,' . $featureId;
                    $rulesEditdFeature['feature_variant.description.' . $keyFV] = 'regex:/(^[A-Za-z0-9 ]+$)+/|max:255';
                    $messagesEditFeature['feature_variant.name.' . $keyFV . '.regex'] = 'Name can contain alphanumeric characters and spaces only';
                    $messagesEditFeature['feature_variant.name.' . $keyFV . '.max'] = 'Name is too long to use. 255 characters max.';
                    $messagesEditFeature['feature_variant.name.' . $keyFV . '.unique'] = 'Variant name already in use.';
                    $messagesEditFeature['feature_variant.description.' . $keyFV . '.regex'] = 'Invalid description.';
                }

                $validator = Validator::make($request->all(), $rulesEditdFeature, $messagesEditFeature);
                if ($validator->fails()) {
                    return Redirect::back()
                        ->with(["code" => '400', 'message' => 'Please correct the following errors.'])
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    $dataUpdateFeature['feature_name'] = $request->input('feature_name');
                    $dataUpdateFeature['full_description'] = $request->input('full_description');
                    $dataUpdateFeature['feature_type'] = $request->input('feature_type');
                    $selectedCategories = $request->input('for_categories');
                    $dataUpdateFeature['for_categories'] = implode(',', array_keys($selectedCategories));
                    $dataUpdateFeature['display_on_product'] = 0;
                    if ($request->input('display_on_product') != null) {
                        $dataUpdateFeature['display_on_product'] = 1;
                    }
                    $dataUpdateFeature['display_on_catalog'] = 0;
                    if ($request->input('display_on_catalog') != null) {
                        $dataUpdateFeature['display_on_catalog'] = 1;
                    }
                    $dataUpdateFeature['group_flag'] = 0;
                    $whereForFeatureUpdate = [
                        'rawQuery' => 'feature_id = ?',
                        'bindParams' => [$featureId]
                    ];
                    $updatedFeature = json_decode($objModelFeatures->updateFeatureWhere($dataUpdateFeature, $whereForFeatureUpdate), true);
                    //CODE TO INSERT VARIANTS HERE
                    $whereForVariantId = ['rawQuery' => 'feature_id =?', 'bindParams' => [$featureId]];
                    $selectedVariantColumn = array(DB::raw('GROUP_CONCAT(variant_id) AS variant_ids'));
                    $oldVariantIds = explode(',', json_decode($objModelFeatureVariants->getFeatureVariantWhere($whereForVariantId, $selectedVariantColumn), true)['data']['variant_ids']);
                    $inputVariantIds = array();
                    $updateVariantResultFlag = false;
                    $newVariantResultFlag = false;
                    $deletedVariantResultFlag = false;
                    $variantIdsToDelete = $oldVariantIds;
                    if ($dataUpdateFeature['feature_type'] != 0) {
                        foreach ($featureVariants as $keyFV => $valueFV) {
                            if ($valueFV != '') {
                                if (isset($featureVariantIds[$keyFV]) && in_array($featureVariantIds[$keyFV], $oldVariantIds)) {//UPDATE VARIANT DETAILS
                                    $inputVariantIds[] = $featureVariantIds[$keyFV];
                                    $updateVariantData = '';
                                    $updateVariantData['variant_name'] = $valueFV;
                                    $updateVariantData['description'] = $featureVariantsDesc[$keyFV];
                                    $whereForUpdateVariant = ['rawQuery' => 'variant_id =?', 'bindParams' => [$featureVariantIds[$keyFV]]];
                                    $updateVariantResult = $objModelFeatureVariants->updateFeatureVariantWhere($updateVariantData, $whereForUpdateVariant);
                                    if ($updateVariantResult) {
                                        $updateVariantResultFlag = true;
                                    }
                                } else {//ADD NEW VARIANT DETAILS
                                    $newVariantData['feature_id'] = $featureId;
                                    $newVariantData['variant_name'] = $valueFV;
//                            $newVariantData['added_by'] = $userId;
                                    $newVariantData['description'] = $featureVariantsDesc[$keyFV];
                                    $newInsertedVariantId = $objModelFeatureVariants->addFeatureVariant($newVariantData);
                                    if ($newInsertedVariantId) {
                                        $newVariantResultFlag = true;
                                    }
                                }
                            }

                        }
                        $variantIdsToDelete = array_diff($oldVariantIds, $inputVariantIds);
                    }

                    if (!empty($variantIdsToDelete)) {
                        $whereForDeleteVariant = ['rawQuery' => 'variant_id IN (?)', 'bindParams' => [implode(',', $variantIdsToDelete)]];
                        $deletedVariantResult = $objModelFeatureVariants->deleteFeatureVariantWhere($whereForDeleteVariant);
                        if ($deletedVariantResult) {
                            $deletedVariantResultFlag = true;
                        }
                    }

                    if ((isset($updatedFeature) && $updatedFeature['code'] == 200) ||
                        (isset($updateVariantResultFlag) && $updateVariantResultFlag) ||
                        (isset($newVariantResultFlag) && $newVariantResultFlag) ||
                        (isset($deletedVariantResultFlag) && $deletedVariantResultFlag)
                    ) {//ALL DETAILS UPDATED
                        return Redirect::back()->with(['code' => '200', 'message' => 'Changes saved successfully.']);
                    } else {//NOTHING TO UPDATE
                        return Redirect::back()->with(['code' => '200', 'message' => 'Nothing to update.']);
                    }
                }
            }
            return view('Supplier/Views/features/editFeature', ['code' => '', 'featureGroups' => $allFeatureGroups, 'allCategories' => $allCategories, 'featureDetails' => $featureDetails, 'fvData' => json_decode($fvData, true)]); //, ['successMsg' => $successMsg, 'errMsg' => $errMsg]
        } else {
            return view('Supplier/Views/features/editFeature', ['code' => '400', 'message' => 'No such feature found', 'status' => 'error']); //, ['successMsg' => $successMsg, 'errMsg' => $errMsg]
        }

    }

}
