<?php
namespace FlashSale\Http\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;
class Pages extends  Model
{
    private static $_instance = null;

    protected $table = 'pages_extra';
    protected $fillable = ['page_id', 'page_name', 'page_title', 'page_content_url', 'page_status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Pages();
        return self::$_instance;
    }

            public function getAllPDwhere($where,$selectedColumns=['*'])// All page Details
            {
                $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
                if(func_num_args()>0) {
                    $where = func_get_arg(0);
                    $result = DB::table($this->table)
                        ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                        ->select($selectedColumns)
                        ->first();
                    if ($result) {
                        $returnData['code'] = 200;
                        $returnData['message'] = 'All reward settings.';
                    } else {
                        $returnData['code'] = 400;
                        $returnData['message'] = 'No data found';
                    }
                    $returnData['data'] = $result;
                }
                return json_encode($returnData);
        }

/**@pagetitle
 *
 *
 **/


    public function getAllPTwhere()
    {
        $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0) {
            $selectedColumns = func_get_arg(0);
            $result = DB::table($this->table)
                ->select($selectedColumns)
                ->get();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All reward settings.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

}