<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class Module extends Model implements AuditableContract
{
    
    use Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_description','application', 'description','created_at', 'updated_at', 'active','app_module_id'
        
    ]; 
    protected $auditInclude = [
        'application_description','application', 'description','created_at', 'updated_at', 'active',
        
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
