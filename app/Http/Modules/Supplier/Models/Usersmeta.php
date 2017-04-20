<?php

namespace FlashSale\Http\Modules\Supplier\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;


class Usersmeta extends Model
{

    protected $table = 'usersmeta';
    protected $fillable = ['user_id', 'addressline1', 'addressline2', 'city', 'state', 'country', 'zipcode', 'phone'];
    private static $_instance = null;

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Usersmeta();
        return self::$_instance;
    }

    public function getUsersMetaDetailsWhere()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->where($where)
                ->first();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * Update user data
     * @param array $data
     * @param array $where
     * @return mixed|int
     * @throws Exception
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function updateUsersMetaDetailsWhere()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $updatedResult = DB::table($this->table)
                    ->where($where)
                    ->update($data);
                return $updatedResult;
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getUsersMDWhere($where1,$select)
    {

        if (func_num_args() > 0) {
            $where1 = func_get_arg(0);
            $select = func_get_arg(1);
            try {
                $result = DB::table('usersmeta')
                    ->leftjoin('location as lt1', 'usersmeta.country', '=', 'lt1.location_id')
                    ->leftjoin('location as lt2', 'usersmeta.state', '=', 'lt2.location_id')
                    ->leftjoin('location as lt3', 'usersmeta.city', '=', 'lt3.location_id')
                    ->whereRaw($where1['rawQuery'], isset($where1['bindParams']) ? $where1['bindParams'] : array())
                ->select('country','state','city','lt1.name as country_name','lt2.name as state_name','lt3.name as city_name','usersmeta.*')
                    ->first();

                return $result;

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }
//    public function updateUsersMetaDetailsWhere2($where)
//    {
//
//
//            try{
//            $updatedResult = DB::table('usersmeta')
//                ->leftJoin('location as lt', function ($join) {
//                    $join->on('lt.location_id', '=', 'usersmeta.country');
//                })
//                ->leftJoin('location as lt1', function ($join) {
//                    $join->on('lt1.location_id', '=', 'usersmeta.state');
//                })
//                ->leftJoin('location as lt2', function ($join) {
//                    $join->on('lt2.location_id', '=', 'usersmeta.city');
//                })
//                ->get(['lt.name as country_name','lt1.name as state_name','lt2.name as city_name','usersmeta.*']);
//            return $updatedResult;
//            } catch (\Exception $e) {
//                return $e->getMessage();
//            }
//
//
//        }
    }



