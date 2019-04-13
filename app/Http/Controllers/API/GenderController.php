<?php
/**
 * Created by PhpStorm.
 * User: wayne
 * Date: 09/04/2019
 * Time: 20:07
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GenderController extends Controller
{
    public function getAllGenders()
    {
        $genders = DB::table('gender')->get();
        return $genders;
    }

}