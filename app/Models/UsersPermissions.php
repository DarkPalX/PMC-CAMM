<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class UsersPermissions extends Model implements AuditableContract
{
    use Auditable;
    
    protected $fillable = [
        'username', 
        'permission_id', 
        'module_id',
        'app_id',
        'action',
    ];
    protected $auditInclude = [
        'username', 
        'permission_id', 
        'module_id',
        'app_id',
        'action',
    ];

    
}
