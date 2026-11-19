<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class AccessedApp extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;
    protected $fillable = ['username','app','dateAccessed','isActive'];
    protected $auditInclude = ['username','app','dateAccessed','isActive'];
}
