<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Audit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type', 'user_id','event', 'role', 'application_type', 'module_type', 'old_values','new_values', 'url', 'ip_address', 'created_at','updated_at', 'active',
        
    ];    
}