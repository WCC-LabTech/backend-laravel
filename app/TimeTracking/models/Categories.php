<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 3/27/14
 * Time: 11:23 AM
 */

namespace TimeTracking\models;

use Eloquent, Validaton, Exception;
class Categories extends Eloquent {

    private $table = 'time_tracking_categories_table';
    private $fillable = 'category';
    private $guarded  = array('id');
    private $timestamps = false;


    public function entry(){

        return $this->belongsTo('user');
    }


} 