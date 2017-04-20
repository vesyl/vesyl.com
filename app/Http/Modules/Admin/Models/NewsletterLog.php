<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Newsletter log model
 * Class Newsletters
 * @package FlashSale\Http\Modules\Admin\Models
 */
class NewsletterLog extends Model
{

    private static $_instance = null;

    protected $table = 'newsletter_log';
    protected $fillable = ['newsletter_log_subject', 'content', 'content_date'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new NewsletterLog();
        return self::$_instance;
    }

    public function addNewsletter()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getNewsletterDetail($selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->select()
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