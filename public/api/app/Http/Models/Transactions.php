<?php
namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Currency model
 * Class Currency
 * @package FlashSaleApi\Http\Modules\Admin\Models
 * @author Dinanath Thakur <dinanaththakur@globussoft.in>
 */
class Transactions extends Model
{

    private static $_instance = null;

    protected $table = 'transactions';


    /**
     * Get instance/object of this class
     * @return Orders|null
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 13-07-2016
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Transactions();
        return self::$_instance;
    }

    public function insertToTransaction()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);

            try {
                $result = DB::table($this->table)->insert($data);
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * @return json string
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function addTransaction()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data, 'transaction_id');
                return json_encode(array('code' => 200, 'message' => 'Transaction added successfully.', 'data' => $result));
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
        }
    }
}

?>