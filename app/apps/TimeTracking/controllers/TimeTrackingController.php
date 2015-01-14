<?php
/**
 * @author: brian
 *
 *
 */

namespace TimeTracking\controllers;

use BaseController, Input, User,  Entry ,Response, View, Redirect;
use Illuminate\Support\Facades\Auth;
use TimeTracking\models\Categories;
use TimeTracking\models\TimeTrackingEntry;
use TimeTracking\models\TimeTrackingPayPeriod;
/**
* This is an implementation of @see 
* /app/apps/TimeTracking/models/TimeTrackingEntry
* This will allow the user to create a new punched in time,
* and end time . With this class it will also allow to modify
* time , delete time . 
* All private functions are located at the bottom .
*/
class TimeTrackingController extends  BaseController{

    public function getIndex($pay_period) {
        
        return Response::json(TimeTrackingEntry::where(array('pay_id' => $pay_period, 'user_id' => Auth::user()->id)));
    }

    /**
    * This function will create an new TimeTrackingEntry 
    * object. This function calls the Auth::user() to validate 
    * the users id for more information see 
    * @link http://laravel.com/docs/security .
    * This function calls a helpe function @see validateTime() to
    * validate time. 
    * @throws an exception if the obect can't be saved.
    */
    public function postCreate(){

        $timeEntry = new TimeTrackingEntry();
        $timeEntry->user_id = Auth::user()->id;
        $input = Input::all();
        
            $this->postAddTime($timeEntry);
            try{
                $timeEntry->save();
                return Redirect::back()->with('message', 'Time Entry Created')->with('alert', 'success');
            }catch (exception $e){
                return Redirect::back()->with('message', 'Time Entry Creation Failed')->with('alert', 'danger');
            }
    }
    /**
    * This function will allow the user to retrieve a time based 
    * on the id . This function calls find() which is inherited from  
    * BaseController @link http://laravel.com/docs/eloquent
    * which extends eloquent. If found it will delete the entery
    */
    public function getDelete($id){

        $timeEntry = TimeTrackingEntry::find($id);

        if ($timeEntry['user_id'] != Auth::user()->id) {
          return Response::json(array('status' => 401, 'message' => 'You can only delete your own Entrys'), 401);
        }

        try{
            $timeEntry->delete();
            return Redirect::back()->with('message', 'Entry Deleted')->with('alert', 'success');
        }catch (exception $e){
            return Redirect::back()->with('message', 'Entry Deletion Failed')->with('alert', 'danger');
        }
    }
    /**
    *
    * This will allow the use to modify a time that was entered into
    * the database. This function calls the helper function findOrFail()
    * @link http://laravel.com/docs/eloquent for more information on that.
    * After the time has been validated @see validateTime() it will add the 
    * time via helper function @see postAddTime(), and then save the time.
    * @throws exception if the time couldn't be saved.
    *****************Jason Comments************
    * the find or fail call was universally failing, so replaced with just find. it also needed to be
    * Input::get('id') instead of just 'id' as shown in the laravel documentation example
    * the validate time if statement was returning false as well even on good data, which lead to the odd situation where 
    * this call was not returning content leading to an error, so removed it for the time
    * also changed the responces to redirect so you would be back on the same page you started
    * TO DO: validate time
    */
    public function postModifyTime(){

        $timeEntry = TimeTrackingEntry::find(Input::get('id'));  //OrFail('id')->get();
//        if($this->validateTime(Input::get('start_time')) && $this->validateTime(Input::get('end_time')) ){

        if ($timeEntry['user_id'] != Auth::user()->id) {
          return Response::json(array('status' => 401, 'message' => 'You can only delete your own Entrys'), 401);
        }

        $timeEntry->category_id = Input::get('modify_category_id');
        $timeEntry->startTime = Input::get('modify_start_time');
        $timeEntry->startDate = Input::get('modify_start_date');
        $timeEntry->endDate = Input::get('modify_end_date');
        $timeEntry->endTime   = Input::get('modify_end_time');
        $timeEntry->description = Input::get('description');
        $timeEntry->pay_id = Input::get('pay_id');       

        if (Input::get('modify_clock_in') == "yes") {
          $timeEntry->clocked_in = "1";
        } else {
          $timeEntry->clocked_in = "0";
        }

        try{
            $timeEntry->save();
            return Redirect::back()->with('message', 'Time Entry Changed')->with('alert', 'success');
        }catch (exception $e){
            return Redirect::back()->with('message', 'Time Change Failed')->with('alert', 'danger');
        }
    }
    /*
    Alternet version of the modiy time.
    public function postModifyTime(){
        
        if(Auth::validate(Input::get('id')) )
            TimeTrackingEntry::update(TimeTrackingEntry::find('id'));

    }
     
    */
    public function getEntries($pay_period) {
        $categories = Categories::all();
        $time = TimeTrackingEntry::user()->period($pay_period)->get();
	$maxid = TimeTrackingPayPeriod::max('id');
	if ($maxid == $pay_period || $maxid - 1 == $pay_period)
		$current = 1;
	else
		$current = 0;
//	$current = TimeTrackingPayPeriod::whereRaw('id = (select max(`id`) from TimeTrackingPayPeriod)')->get();
//	$current = lab_tech::table('time_tracking_pay_period')->max('id');
        $this->layout->content = View::make('time/entries', array('entries' => $time, 'categories' => $categories, 'pay_id' => $pay_period, 'current' => $current));
         
    }

    public function getCategories(){
         
      return  Response::json(array('category' => Categories::select('category')
            ->where('id', '=' ,Input::get('category_id') )->toArray() ) );
    }
    /**
    * This function will retreive the current pay period and return the 
    * dates worked for the user to see .  
    * @return $payperiod for the user  
    */
    public function getPayDates()
    {

    return Response::json(TimeTrackingEntry::where('pay_id' , ' = ' , Input::get('pay_id') )
    ->select( 'start_time' , 'end_time' , 'start_date' , 'end_date')->all()->toArray() ); 

    }
    
    public function getAllPayDays(){
        return Response::json(TimeTrackingPayPeriod::all()->toArray() );
    }

    public function missingMethod($parameters = array()){
        return Response::json(array('status' => 404, 'message' => 'Not found'), 404);
    }
    /**
    * This is a helper function that will do all the work for class
    * in the form of adding or modifying time . This function calls
    * Input @link http://laravel.com/docs/requests for more information
    * on this , and saves the correct fields it the correct places in 
    * the database. 
    */
    private function postAddTime($timeEntry){

        $timeEntry->category_id = Input::get('category_id');
        $timeEntry->startTime = Input::get('start_time');
        $timeEntry->startDate = Input::get('start_date');
        $timeEntry->endDate = Input::get('end_date');
        $timeEntry->endTime   = Input::get('end_time');
        $timeEntry->description = Input::get('description');
        $timeEntry->pay_id = Input::get('pay_id');
        if (Input::get('clock_in') == "yes") {
          $timeEntry->clocked_in = "1";
        } else {
          $timeEntry->clocked_in = "0";
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
        $seconds = 0;//$timeExplode[2];

        if($hours <= 12 && $minutes < 60 && $seconds < 60)
            return (($hours > 0 &&  $minutes >= 0 && $seconds >= 0));

    return false; // Time wasn't valid..

    }


}
