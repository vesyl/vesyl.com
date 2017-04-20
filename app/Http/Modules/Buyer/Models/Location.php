<?php

namespace FlashSale\Http\Modules\Buyer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{

    private static $_instance = null;

    protected $table = 'location';

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Location();
        return self::$_instance;
    }

    public function getAllLocationsWhere($where, $selectedColumns = ['*'])
    {

        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->get();
        return $result;
    }


    public function getAllCountries(){
        if (func_num_args()>0) {
            $where=func_get_arg(0);
            $select=func_get_arg(1);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'],isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($select)
                ->get();
            return $result;
        }

    }


}
