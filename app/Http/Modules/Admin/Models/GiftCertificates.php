<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * AdminGiftCertificate model
 * Class AdminGiftCertificate
 * @package FlashSale\Http\Modules\Admin\Models
 */
class GiftCertificates extends Model
{

    private static $_instance = null;

    protected $table = 'gift_certificates';
    protected $fillable = ['gift_certificate_id', 'gift_id','gif_by','gif_for','gift_amount','gift_balance', 'gift_name','gift_message', 'gift_code','added_date', 'gift_status', 'redeem_status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new GiftCertificates();
        return self::$_instance;
    }

    public function giftCertificateList($selectedColumns){

        try {
            $result = DB::table($this->table)
                ->join('users as gift_by','gift_by.id','=','gift_certificates.gift_by')
                ->join('users as gift_for','gift_for.id','=','gift_certificates.gift_for')
                ->select($selectedColumns)
                ->get();

        } catch (QueryException $e) {
            echo $e;
        }
        if ($result) {
            return $result;
        } else {
            return 0;
        }

    }



}