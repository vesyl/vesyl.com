<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;
class Extrapages extends  Model{
    private static $_instance = null;

    protected $table = 'pages_extra';
    protected $fillable = ['page_id', 'page_name', 'page_title','page_content_url','page_status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Extrapages();
        return self::$_instance;
    }



    public function getAllPDwhere($selectedColumns=['*']) //Page details
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

    public function getAllUSwhere($where,$selectedColumns=['*'])// Get All user detils
    {
        $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0) {
            $where = func_get_arg(0);
//                    $result=func_get_arg(1);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($selectedColumns);
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

    public function getAllANwhere()// Add new pages
    {
        $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0) {
            $data = func_get_arg(0);
//                    $result=func_get_arg(1);
            $result = DB::table($this->table)
                ->insert($data);
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


    public function getAllUPwhere() // Status upadte
    {
        $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0) {
            $data = func_get_arg(0);
                    $where=func_get_arg(1);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($data);
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

    public function getAllPageDetailswhere() //Page details
    {
        $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0) {
            $where = func_get_arg(0);

            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
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



    public function getAllEditupdatewhere() // Status upadte
    {
        $returnData=array('code'=>400,'message'=>'Argument Not Passed','data'=>null);
        if(func_num_args()>0) {
            $data = func_get_arg(0);
            $where=func_get_arg(1);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($data);
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