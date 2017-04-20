<?php

namespace FlashSale\Http\Middleware;

use Closure;
use FlashSale\Http\Modules\Supplier\Models\Shop;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth as AuthUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use FlashSale\Http\Modules\Supplier\Models\User;

class SubdomainAuthentication
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {

        $this->auth = $auth;
        $lang = Session::get('locale');

        if ($lang != null) \App::setLocale($lang);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd("sss");
        $objModelShop = Shop::getInstance();
        $urlmain = $_SERVER['HTTP_HOST'];
        $domain = env("WEB_URL");
        $shopname = "";

        if ($urlmain != $domain) {

            $shopurl = explode("." . $domain, $urlmain);
            $shopname = $shopurl[0];
        }
        $where = [
            'rawQuery' => 'shop_name = ?',
            'bindParams' => [$shopname]
        ];

        $columns = ["shop_name"];
        $userData = $objModelShop->getShopDataWhere($where, $columns);


        $decodedUserData = json_decode($userData, true);
//        Session::forget("domainname");
        if ($decodedUserData["code"] == 200) {

            if (!Session::has("domainname")) {
                Session::put("domainname", $shopname . ".");
//                dd("Asd");
            }
//            dd("Asd");

        } else {
            dd("there is no such shop");

        }
        return $next($request);


    }


}
