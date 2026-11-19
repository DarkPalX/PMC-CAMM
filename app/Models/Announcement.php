<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class Announcement extends Model implements AuditableContract
{
    use Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'range_from','range_to','recipient','message', 'created_at', 'active','readBy'
        
    ];   
    protected $auditInclude = [
        'title', 'range_from','range_to','recipient','message', 'created_at', 'active','readBy'
        
    ];  

}
