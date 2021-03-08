<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Sales_orders extends Model
{

    public $fillable = ['id','user_id','brand_name','model_name','issue_name','address','landmark','order_date'];
}