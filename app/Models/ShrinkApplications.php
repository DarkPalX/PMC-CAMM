<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class ShrinkApplications extends Model implements AuditableContract
{
    use Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'app_id', 'description','created_at','updated_at','database_name'
        
    ];   
    protected $auditInclude = [
        'app_id', 'description','created_at','updated_at','database_name'
        
    ];  

}
