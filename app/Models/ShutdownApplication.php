<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class ShutdownApplication extends Model implements AuditableContract
{
    use Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scheduled_date', 
        'scheduled_time',
        'reason',
        'posted_date',
        'posted_time',
        'apps',
        'deleted_at'
        
    ];    

    protected $auditInclude = [
        'scheduled_date', 
        'scheduled_time',
        'reason',
        'posted_date',
        'posted_time',
        'apps',
        'deleted_at'
        
    ];   

    protected $appends = ['appsdesc'];

    public function getAppsDescAttribute()
    {
        $apps = $this->apps;
        $appArr = "";
        $appdesc = "";
        $appsDesc = "";
        if ($apps == null) {
            $apps = "";
        }
        if ($apps != "") {
            $appArr = explode(",", $this->apps);
            foreach ($appArr as $app) {
                $appdesc = Application::find($app[0]);
                if ($appsDesc == "") {
                    $appsDesc = $appdesc->description;
                } else {
                    $appsDesc = $appsDesc . ", " . $appdesc->description;
                }
            }
        }
        return $appsDesc;
    }
    // public function getStatusAttribute()
    // {
    //     $status = 'Active';
    //     if ($this->active != 1) {
    //         $status  = 'Inactive';
    //     }
    //     return $status;
    // }

}
