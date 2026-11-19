<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

 protected $fillable=['application','isDaily','hrs','isWeekly','week_day','isMonthly','month_date','logDays'];
 protected $auditInclude=['application','isDaily','hrs','isWeekly','week_day','isMonthly','month_date','logDays'];
}
