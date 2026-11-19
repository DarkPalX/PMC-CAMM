<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class Permission extends Model implements AuditableContract
{
    use Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_type', 'module_description', 'application_description', 'description','created_at','updated_at', 'active','app_permission_id','application'
        
    ]; 
    protected $auditInclude = [
        'module_type', 'module_description', 'application_description', 'description','created_at','updated_at', 'active',
        
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
