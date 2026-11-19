<?php

namespace App\Services;

use App\Repositories\Interfaces\AccessedAppRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccessedAppService
{
    protected $repository;

    public function __construct(AccessedAppRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        $accessedapps = $this->repository->all();

        $data = collect();
        foreach ($accessedapps as $accessedapp) {
            $data->push([
                'username' => $accessedapp->username,
                'app' => $accessedapp->app,
                'dateAccessed' =>date('Y-m-d',strtotime($accessedapp->dateAccessed)),
                'IsActive' => $accessedapp->IsActive,
            ]);
        }

        return $data;
    }

    public function create($fields)
    {
        $data = [
            'username' => $fields->username,
            'app' => strtoupper($fields->app),
            'dateAccessed' => Carbon::now(),
            'isActive' => 1,
        ];

        $access = $this->repository->create($data);

        if ($access) {
            return $access;
        } else {
            return redirect()->back()->with('errorMesssage', 'Adding record failed.');
        }
    }

}
