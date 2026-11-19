<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class Application extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;
    protected $fillable = ['id', 'description', 'active', 'server_name', 'database_name', 'dbusername', 'dbpass', 'app_url', 'loginFieldName','updated_at_reindex','syncDate','updated_at_shrink'];
    protected $auditInclude = ['id', 'description', 'active', 'server_name', 'database_name', 'dbusername', 'dbpass', 'app_url', 'loginFieldName','updated_at_reindex','syncDate','updated_at_shrink'];

    protected $appends = ['roles', 'dateAccessed', 'status', 'modules'];

    public function getRolesAttribute()
    {
        $role = [];
        $app_roles = Role::where('app', $this->description)->get();
        foreach ($app_roles as $app_role) {
            $data = [
                'id' => $app_role->app_role_id,
                'name' => $app_role->name,
            ];
            array_push($role, $data);
        }
        return $role;
    }
    public function getModulesAttribute()
    {
        $module = [];
        $app_modules = Module::where('application', $this->id)->get();
        // if ($app_modules->count() > 0) {
            foreach ($app_modules as $app_module) {
                $data = [
                    'id' => $app_module->app_module_id,
                    'name' => $app_module->description,
                ];
                array_push($module, $data);
            }
        // }
        return $module;
    }
    public function getDateAccessedAttribute()
    {
        $AccessedApp = AccessedApp::where('app', $this->id)->orderBy('dateAccessed', 'desc')->first();
        // dd($AccessedApp);
        if ($AccessedApp) {
            return $AccessedApp->dateAccessed;
        }
        return "";
    }

    public function getStatusAttribute()
    {
        $status = 'Active';
        if ($this->active != 1) {
            $status  = 'Inactive';
        }
        return $status;
    }
}
