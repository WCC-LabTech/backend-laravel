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

        $this->postAddTime($timeEntry,$input);
        
        try{
            
            $timeEntry->save();
            Response::json('Message', 200);
            
        }catch (exception $e){
                Response::json('Message',$e, 400 );
        }

    }
    
    public function postCreateTimeOrModify(){

        $userTime = TimeTrackingEntry::where('id' , ' = ' , Input::get('id'))->count();

        if ($userTime != 0) {
            $this->postAddTime($userTime = new TimeTrackingEntry(), Input::all());
            try{

                $userTime->save();
                Response::json('Message', 200);

            }catch(exception $e){
                  Response::json('Message',400);  
            }

        }
        else{
            
            $timeEntry = TimeTrackingEntry::find('id')->get();
            $this->postAddTime($timeEntry, Input::all() );
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

        $timeEntry = TimeTrackingEntry::find('id')->get();
        $this->postAddTime($timeEntry, Input::all() );
        
        try{
            
            $timeEntry->save();
            Response::json('Message','Time saved');
            
            }catch (exception $e){
                Response::json('Message',$e);
            }
    }
    
    public function getPayDates(){

    $entry = DB::$table('time_tracking_entry')->where('pay_id' , ' = ' , Input::get('pay_id') )
    ->select( 'start_time' , 'end_time' , 'start_date' , 'end_date');

    return $entry; 

    }

    public function missingMethod($parameters = array()){
        return Response::json(array('status' => 404, 'message' => 'Not found'), 404);
    }

    private function postAddTime($timeEntry, $input){
            
        if($this->validateTime(Input::get('start_time')) 
            && $this->validateTime(Input::get('end_time')) ){
            
            $timeEntry->category_id = $input['category_id'];
            $timeEntry->pay_id      = $input['pay_id'];
            $timeEntry->startTime = $input['start_time'];
            $timeEntry->startDate = $input['start_date'];
            $timeEntry->endDate = $input['end_date'];
            $timeEntry->endTime   = $input['end_time'];
            $timeEntry->description = $input['description'];
         
         }
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

    return false; 

    }
}