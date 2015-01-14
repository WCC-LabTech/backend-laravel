<?php

View::name('layouts.layout', 'layout');
$layout = View::of('layout');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function() use($layout) {
	return $layout->nest('content', 'home');
});


Route::post('login', 'UserController@login');
Route::get('logout', 'UserController@logout');

Route::post('passwordRecovery', 'UserController@passwordRecovery');
Route::get('passwordReset/{token}', 'UserController@passwordResetForm');
Route::post('passwordResetSubmit', 'UserController@passwordReset');


/*	Must be Authenticated  - auth grouping
-----------------------*/
Route::group(array('before' => 'auth'), function() use($layout){

	/*	Admin panel grouping
		Must be an Admin
	-----------------------*/
	Route::group(array('before' => 'admin'), function() use($layout){

		Route::group(array('prefix' => 'admin/'), function() use($layout){

			Route::get('payroll', 'TimeTracking\controllers\TimeTrackingPayPeriodController@index');

			Route::group(array('prefix' => 'time/'), function() use($layout) {
				Route::post('createPayPeriod', 'TimeTracking\controllers\TimeTrackingPayPeriodController@postCreatePayPeriod');
				Route::controller('categories', 'TimeTracking\controllers\TimeTrackingCategories');
				Route::get('viewpay/{id}', 'TimeTracking\controllers\TimeTrackingPayPeriodController@getViewPay');
			});


			/*	admin USER
			-----------------------*/
			Route::controller('user', 'UserController');



			/*	Schedule Management grouping
			-----------------------*/
			Route::group(array('prefix' => 'schedule'), function() use($layout){


				Route::controller('/skill', 'Lotto\controllers\SkillController');
				
				Route::controller('/course', 'Lotto\controllers\CourseController');

				/*	Schedule Management root
				-----------------------*/	
				Route::controller('/', 'Lotto\controllers\AdminController');


			}); // end of schedule management group

			// Group Study Admin Controller
			Route::controller('study', 'GroupStudy\controllers\ReportController');
			
			
		}); // end of admin auth

	}); // end of admin group
		

	/*	Schedule group User Side
	-----------------------*/
	Route::group(array('prefix' => 'schedule/'), function() use ($layout) {

		Route::controller('user', 'Lotto\controllers\UserController');

		/*	Availability display
		-----------------------*/
		Route::controller('availability', 'Lotto\controllers\AvailabilityController');

	}); // end of schedule group user side






	Route::group(array('prefix' => 'group_study/'), function() use($layout) {

		Route::get('/', function() use($layout) {
			return $layout->nest('content', 'study.home');
		});

		Route::controller('entry', 'GroupStudy\controllers\EntryController');
		Route::controller('report', 'GroupStudy\controllers\ReportController');


		//Route::match(array('GET', 'POST'), '/checkPunchedIn', 'GroupStudy\controllers\EntryController@checkPunchedIn');
		//Route::match(array('GET', 'POST'), '/StartEntry', 'GroupStudy\controllers\EntryController@StartEntry');

	});

	Route::group(array('prefix' => 'calendar/'), function() {

		Route::controller('entries', 'Calendar\controllers\EntriesController');
	});


    Route::group(array('prefix' => 'time/'), function() {
        Route::controller('entries', 'TimeTracking\controllers\TimeTrackingController');
        Route::controller('payperiods', 'TimeTracking\controllers\TimeTrackingPayPeriodController');
    });



	Route::group(array('prefix' => 'inventory/'), function() {
		Route::controller('equipment', 'Inventory\controllers\EquipmentController');
		Route::controller('component', 'Inventory\controllers\ComponentController');
		Route::controller('contract', 'Inventory\controllers\ContractController');
	});

}); // end of auth group
