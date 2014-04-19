<?php
/**
 * @author: brian
 *
 *
 */

namespace TimeTracking\controllers;

use BaseController, Input, User,  Entry ,Response;
use Illuminate\Support\Facades\Auth;
use TimeTracking\models\Categories;
use TimeTracking\models\TimeTrackingEntry;

class TimeTrackingController extends  BaseController{

    public function postCreateTime(){

        $timeEntry = new TimeTrackingEntry();
        $timeEntry->user_id = Auth::user()->id;
        $input = Input::all();

        if($this->validateTime(Input::get('start_time')) && $this->validateTime(Input::get('end_time')) ){
            $this->postAddTime($timeEntry);
            try{
                $timeEntry->save();
                Response::json('Message','Time saved');
            }catch (exception $e){
                Response::json('Message',$e);
            }
        }

    }

    public function postDeleteTime(){

        $timeEntry = TimeTrackingEntry::find('id');

        try{
            $timeEntry->delete();
            Response::json('Message', 'deleted');
        }catch (exception $e){
            return Response::json('Message' , $e);
        }



    }

    public function postModifyTime(){

        $timeEntry = TimeTrackingEntry::findOrFail('id')->get();

        if($this->validateTime(Input::get('start_time')) && $this->validateTime(Input::get('end_time')) ){
            $this->postAddTime($timeEntry);
            try{
                $timeEntry->save();
                Response::json('Message','Time saved');
            }catch (exception $e){
                Response::json('Message',$e);
            }
        }

    }

    public function missingMethod($parameters = array()){
        return Response::json(array('status' => 404, 'message' => 'Not found'), 404);
    }

    private function postAddTime($timeEntry){

        $timeEntry->category_id = Input::get('category_id');
        $timeEntry->startTime = Input::get('start_time');
        $timeEntry->startDate = Input::get('start_date');
        $timeEntry->endDate = Input::get('end_date');
        $timeEntry->endTime   = Input::get('end_time');
        $timeEntry->description = Input::get('description');

    }
    /**
     * This is a simple time validation to make sure the time is
     * okay before storing it in the database
     * @param $time that's being passed in to validate
     * @return bool true if the time is valid.
     */

    private function  validateTime($time){

        $timeExplode = explode(':',$time);
        $hours = $timeExplode[0];
        $minutes = $timeExplode[1];
        $seconds = $timeExplode[2];

        if($hours <= 12 && $minutes < 60 && $seconds < 60)
            return (($hours > 0 &&  $minutes >= 0 && $seconds >= 0));

    return false; // Time wasn't valid..

    }





}
