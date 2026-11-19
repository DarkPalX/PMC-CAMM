<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class UserApprole extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;
    protected $fillable = ['user_id', 'app_id', 'role_id',];
    protected $auditInclude = ['user_id', 'app_id', 'role_id',];
}
