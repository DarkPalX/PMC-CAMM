<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;
class Role extends Model implements AuditableContract
{
    use Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'description','created_at', 'updated_at', 'active','app','app_role_id'
        
    ];   
    
    protected $auditInclude = [
        'id', 'name', 'description','created_at', 'updated_at', 'active','app','app_role_id'
        
    ];     

    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        $status = 'Active';
        if($this->active != 1){
            $status  = 'Inactive';
        }
        return $status;
    }    
   
}
