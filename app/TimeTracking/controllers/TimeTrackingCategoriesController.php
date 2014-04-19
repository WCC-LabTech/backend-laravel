<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 4/18/14
 * Time: 10:25 PM
 */

namespace TimeTracking\controllers;


use Illuminate\Support\Facades\Input;
use BaseController, User,  Entry ,Response;
use TimeTracking\models\Categories;

class TimeTrackingCategoriesController extends BaseController{


    public function postAddCategory(){

        $input = Input::all();
        $category = new Categories();

        try{
            $category['category'] = $input['category'];
            $category->save();
        }catch (exception $e){
            Response::json('Message',$e);
        }

    }

    public function postEditCategory(){

        $input = Input::all();
        $category = Categories::find($input[id]);
        try{
            $category['category'] = $input['category'];
            $category->save();
            Response::json('Message','category updated' , 200);
        }catch (exception $e){
            Response::json('Message' ,$e,404);
        }

    }

    public function getCategories(){


        $categories = Categories::all()->toArray();

        if($categories->count() > 0)
            Response::json($categories,200);
        else
            Response::json('Message' , 404);
    }
} 