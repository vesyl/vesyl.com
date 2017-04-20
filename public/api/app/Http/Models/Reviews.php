<?php
namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;;

Class Reviews extends Model
{
    private static $_instance = null;

    protected $table = 'reviews';
    protected $fillable = ['review_id', 'review_by', 'review_type', 'review_for', 'review_details', 'review_rating', 'review_status', 'review_status_setby'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Reviews();
        return self::$_instance;
    }

    public function getproductreviews($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not passed', 'data' => null);
       if(func_num_args()>0){
           $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->orderBy('review_id', 'desc')
                ->get();
           if ($result) {
               $returnData['code'] = 200;
               $returnData['message'] = 'All Reqward Settings.';
           } else {
               $returnData['code'] = 400;
               $returnData['message'] = 'No data found';
           }
           $returnData['data'] = $result;
           return $result;
       }
        return json_encode($returnData);

    }

    public function getAlladdReviews($data)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not passed', 'data' => null);
         if(func_num_args()>0){
             $data=func_get_arg(0);
            $result = DB::table('reviews')
                ->insert($data);
             if ($result) {
                 $returnData['code'] = 200;
                 $returnData['message'] = 'All Reqward Settings.';
                 $returnData['data'] = $result;
             } else {
                 $returnData['code'] = 400;
                 $returnData['message'] = 'No data found';
             }
             $returnData['data'] = $result;
             return $result;
         }
        return json_encode($returnData);

    }


    public function deleteUserDetails($where)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not passed', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All Reqward Settings.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found';
            }
            $returnData['data'] = $result;
            return $result;
        }
        return json_encode($returnData);
    }

    public function getAllPRWhere($data)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not passed', 'data' => null);
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $result = DB::table('reviews')
                ->where('review_for', '=', $data['review_for'])
                ->where('review_type', '=', 'P')
                ->where('review_status', '=', 'A')
                ->join('users', 'reviews.review_status_setby', '=', 'users.id')
                ->select('username', 'review_rating', 'review_details')
                ->take(10)->skip($data['start'])->get();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All Reward Settings.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found';
            }
            $returnData['data'] = $result;
            return $result;
        }
        return json_encode($returnData);
    }
     public  function getAllPRcountwhere($data){
         $returnData= array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
         if(func_num_args()>0){
             $data=func_get_arg(0);
             $result = DB::table('reviews')
                 ->where('review_for', '=', $data['review_for'])
                 ->where('review_type', '=', 'P')
                 ->count('review_rating');

             if($result){
                 $returnData['code']=200;
                 $returnData['message']='All Reward settings';
             }else{
                 $returnData['code']=400;
                 $returnData['message']='No data found';

             }
             $returnData['data'] = $result;
             return $result;
         }
         return json_encode($returnData);

     }
    public function getReviewsavg($data){
        $returnData= array('code'=>400, 'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0){
            $data=func_get_arg(0);
            $result = DB::table('reviews')
                ->where('review_for', '=', $data['review_for'])
                ->where('review_type', '=', 'P')
                ->avg('review_rating');
            if($result){
                $returnData['code']=200;
                $returnData['message']='All Reward settings';
            }else{
                $returnData['code']=400;
                $returnData['message']='No data found';

            }
            $returnData['data'] = $result;
            return $result;
        }
        return json_encode($returnData);

    }
}