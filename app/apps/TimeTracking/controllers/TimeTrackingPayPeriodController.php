<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 4/18/14
 * Time: 11:04 PM
 * This class is the implementation of the model @see /app/apps/TimeTracking/models/TimeTrackingPayPeriod.php 
 * This will allow the andim to create, delete and modify the payperiods in the database 
 */

namespace TimeTracking\controllers;

use BaseController, User,  Entry ,Response, TimeTracking\models\TimeTrackingEntry, TimeTracking\models\TimeTrackingPayPeriod, View, Input, Redirect;

class TimeTrackingPayPeriodController extends BaseController{

    public function getIndex() {
        $this->layout->content = View::make('time/payperiod', array('pay_periods' => TimeTrackingPayPeriod::orderBy('id')->get()));
    }

    public function index() {
        $this->layout->content = View::make('admin/time/payperiod', array('pay_periods' => TimeTrackingPayPeriod::all()));
    }

    /**
    * This function is used to create a new payperiod 
    * to be saved to the payperiod table. This function 
    * makes a call to the the helper function and @uses postPayPeriod()
    * create a @see TimeTrackingPayPeriod object and pass it to said function
    */
    public function postCreatePayPeriod(){
   
    $pay_period = new TimeTrackingPayPeriod();
    $this->postPayPeriod($pay_period);
      
    }
   
     
    /**
    * This function @uses TimeTrackingPayPeriod::find(Input::get('id'))
    * to find the given payperiod object if it exists. If it doesn't 
    * it , or if the id was not found it exception 
    * @throws an exception and handels it .   
    */
    public function postDeletePeriod() {

        $period = TimeTrackingPayPeriod::find(Input::get('id'));
        try
        {
          $period->delete();
          Response::json(array('status' => 200, 'message' => 'deletion successful'), 200);
        }
        catch(exception $e)
        {
            Response::json(array('status' => 401, 'message' => 'deletion unsuccessful', 'error' => $e), 401);
        }

    }
    /**
    * This function is like , @see postCreatPayPeriod(), but it will  
    * not create a new object of TimeTrackingPayPeriod
    * and instead will find the payperiod by id number and 
    * @uses TimeTrackingPayPeriod::find() to do so. It will pass 
    * that object to the function @uses postPayPeriod($pay_period )
    * for more information on that function please @see postPayPeriod($pay_period)
    */
    public function postModifyPeriod(){

        $period = TimeTrackingPayPeriod::find(Input::get('id'));
        $this->postPayPeriod($period);

    }
    /**
    * This function is a helper function, @see postModifyPeriod()
    * and @see postCreatPayPeriod(). It will validate time and  
    * make sure the payperiod is unique to avoid duplicate dates.
    * If the date is valid it will store the start date and end in the 
    * @param object that is being passed into the function at call .
    * If the object can not be save it @throws an exception       
    */
    private function postPayPeriod($pay_period){


        if (!$this->failed(Input::all())) {
            $start_date = date("Y-n-j",strtotime(Input::get('start_pay_period')));
            $end_date = date("Y-n-j",strtotime(Input::get('end_pay_period')));
            
            try
            {
                $pay_period['name'] = Input::get('name');
                $pay_period['start_pay_period'] = $start_date;
                $pay_period['end_pay_period']   = $end_date;
                $pay_period->save();
                $this->layout->content = Redirect::to('admin/payroll')->with(array('message' => 'Pay Period Created', 'alert' => 'Success'));
            }
            catch(exception $e){
                $this->layout->content = Redirect::to('admin/payroll')->with(array('message' => 'Pay Period Creation Failed', 'alert' => 'danger'));
            }
        }
        else
            $this->layout->content = Redirect::to('admin/payroll')->with(array('message' => 'Pay Period Creation Failed', 'alert' => 'danger'));
    }

    public function getPayPeriod(){
       return Response::json(array('pay_period' => TimeTrackingPayPeriod::all()->toArray() ) );

    } 
    /**
    * 
    * This is a helper function that validates if the parameter is
    * is unique to the database table. This function calls 
    * @see /app/apps/TimeTracking/models/TimeTrackingPayPeriod for
    * information on that function.  
    * @param $pay_period the payperiod to validate 
    * @return true if it is unique false other wise.
    */
    private function failed($pay_period){
        return TimeTrackingPayPeriod::validate($pay_period)->fails();
    }

    public function getViewPay($id){
//variables
        $query = TimeTrackingEntry::where('pay_id', '=', $id)->orderBy('user_id')-> orderBy('startDate')->get(); //inital query
        $usrcount = 0; //count is just used generally as a counter
        $current = 0; //current and prev are used in seperating out users in the data array
        $prev = 0;
        $curperiod = TimeTrackingPayPeriod::where('id', '=', $id)->get(); //timestamps for pay periods
        $startstamp = strtotime($curperiod[0]['start_pay_period']);
        $midstamp = strtotime($curperiod[0]['start_pay_period'] . ' + 7 days');
        $endstamp = strtotime($curperiod[0]['start_pay_period'] . ' + 14 days');
        $entrystart = 0; //these are for individual stamp entrys
        $entrystop = 0;
        $strvar = ''; //used as one off strings here and there so there's not 50 of them

	$clocked = 0;
        $comment = 0;
        $sat1 = 0;
        $sun1 = 0;
        $mon1 = 0;
        $tue1 = 0;
        $wed1 = 0;
        $thu1 = 0;
        $fri1 = 0;
        $sat2 = 0;
        $sun2 = 0;
        $mon2 = 0;
        $tue2 = 0;
        $wed2 = 0;
        $thu2 = 0;
        $fri2 = 0;

        $data = array();

        foreach($query as $i) {
          $current = $i['user_id'];
          if ($current != $prev) { //if its a new user...
            $prev = $current;
            $usrcount++;
            $strvar = User::find($i['user_id']); //get there name and email
            $data[$usrcount]['name'] = $strvar['first_name'] . ' ' . $strvar['last_name'];
            $data[$usrcount]['email'] = $strvar['email'];
            $comment = 0;
            $sat1 = 0; //and reset day counts for that user
            $sun1 = 0;
            $mon1 = 0;
            $tue1 = 0;
            $wed1 = 0;
            $thu1 = 0;
            $fri1 = 0;
            $sat2 = 0;
            $sun2 = 0;
            $mon2 = 0;
            $tue2 = 0;
            $wed2 = 0;
            $thu2 = 0;
            $fri2 = 0;
          } //end of if prev

          $entrystart = strtotime($i['startDate'] . ' ' . $i['startTime']);
          $entrystop = strtotime($i['endDate'] . ' ' . $i['endTime']);
          $clocked = $i['clocked_in'];
          if ($entrystart < $midstamp && $entrystart > $startstamp) { //week1

            if ( date('D', $entrystart) == 'Sat') {
              $data[$usrcount]['week1']['sat'][$sat1]['start'] = $entrystart;
              $data[$usrcount]['week1']['sat'][$sat1]['end'] = $entrystop;
              $data[$usrcount]['week1']['sat'][$sat1]['clock'] = $clocked;
              $sat1++;
            }
                
            if ( date('D', $entrystart) == 'Sun') {
              $data[$usrcount]['week1']['sun'][$sun1]['start'] = $entrystart;
              $data[$usrcount]['week1']['sun'][$sun1]['end'] = $entrystop;
              $data[$usrcount]['week1']['sun'][$sun2]['clock'] = $clocked;
              $sun1++;
            }         
            if ( date('D', $entrystart) == 'Mon') {
              $data[$usrcount]['week1']['mon'][$mon1]['start'] = $entrystart;
              $data[$usrcount]['week1']['mon'][$mon1]['end'] = $entrystop;
              $data[$usrcount]['week1']['mon'][$mon1]['clock'] = $clocked;
              $mon1++;
            }         
            if ( date('D', $entrystart) == 'Tue') {
              $data[$usrcount]['week1']['tue'][$tue1]['start'] = $entrystart;
              $data[$usrcount]['week1']['tue'][$tue1]['end'] = $entrystop;
              $data[$usrcount]['week1']['tue'][$tue1]['clock'] = $clocked;
              $tue1++;
            }         
            if ( date('D', $entrystart) == 'Wed') {
              $data[$usrcount]['week1']['wed'][$wed1]['start'] = $entrystart;
              $data[$usrcount]['week1']['wed'][$wed1]['end'] = $entrystop;
              $data[$usrcount]['week1']['wed'][$wed1]['clock'] = $clocked;
              $wed1++;
            }         
            if ( date('D', $entrystart) == 'Thu') {
              $data[$usrcount]['week1']['thu'][$thu1]['start'] = $entrystart;
              $data[$usrcount]['week1']['thu'][$thu1]['end'] = $entrystop;
              $data[$usrcount]['week1']['thu'][$thu1]['clock'] = $clocked;
              $thu1++;
            }         
            if ( date('D', $entrystart) == 'Fri') {
              $data[$usrcount]['week1']['fri'][$fri1]['start'] = $entrystart;
              $data[$usrcount]['week1']['fri'][$fri1]['end'] = $entrystop;
              $data[$usrcount]['week1']['fri'][$fri1]['clock'] = $clocked;
              $fri1++;
            }
          
          }//end of week 1

          elseif ($entrystart < $endstamp && $entrystart > $midstamp) { //week2

            if ( date('D', $entrystart) == 'Sat') {
              $data[$usrcount]['week2']['sat'][$sat2]['start'] = $entrystart;
              $data[$usrcount]['week2']['sat'][$sat2]['end'] = $entrystop;
              $data[$usrcount]['week2']['sat'][$sat2]['clock'] = $clocked;
              $sat2++;
            }
                
            if ( date('D', $entrystart) == 'Sun') {
              $data[$usrcount]['week2']['sun'][$sun2]['start'] = $entrystart;
              $data[$usrcount]['week2']['sun'][$sun2]['end'] = $entrystop;
              $data[$usrcount]['week2']['sun'][$sun2]['clock'] = $clocked;
              $sun2++;
            }         
            if ( date('D', $entrystart) == 'Mon') {
              $data[$usrcount]['week2']['mon'][$mon2]['start'] = $entrystart;
              $data[$usrcount]['week2']['mon'][$mon2]['end'] = $entrystop;
              $data[$usrcount]['week2']['mon'][$mon2]['clock'] = $clocked;
              $mon2++;
            }         
            if ( date('D', $entrystart) == 'Tue') {
              $data[$usrcount]['week2']['tue'][$tue2]['start'] = $entrystart;
              $data[$usrcount]['week2']['tue'][$tue2]['end'] = $entrystop;
              $data[$usrcount]['week2']['tue'][$tue2]['clock'] = $clocked;
              $tue2++;
            }         
            if ( date('D', $entrystart) == 'Wed') {
              $data[$usrcount]['week2']['wed'][$wed2]['start'] = $entrystart;
              $data[$usrcount]['week2']['wed'][$wed2]['end'] = $entrystop;
              $data[$usrcount]['week2']['wed'][$wed2]['clock'] = $clocked;
              $wed2++;
            }         
            if ( date('D', $entrystart) == 'Thu') {
              $data[$usrcount]['week2']['thu'][$thu2]['start'] = $entrystart;
              $data[$usrcount]['week2']['thu'][$thu2]['end'] = $entrystop;
              $data[$usrcount]['week2']['thu'][$thu2]['clock'] = $clocked;
              $thu2++;
            }         
            if ( date('D', $entrystart) == 'Fri') {
              $data[$usrcount]['week2']['fri'][$fri2]['start'] = $entrystart;
              $data[$usrcount]['week2']['fri'][$fri2]['end'] = $entrystop;
              $data[$usrcount]['week2']['fri'][$fri2]['clock'] = $clocked;
              $fri2++;
            }

          }//end of week 2

          if ($i['description'] != '') { //if the comment field is not blank add it as a field with its timestamp
            $data[$usrcount]['comment'][$comment]['comment'] = $i['description'];
            $data[$usrcount]['comment'][$comment]['date'] = $entrystart;
            $comment++;
          }//end of if comment 
        } //end of foreach query as i

        $this->layout->content = View::make('admin/time/viewpay', array('entrys' => $data))->with('dates', array(0 => $startstamp, 1 => $midstamp));
    }

} 
