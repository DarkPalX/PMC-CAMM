<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Services\UserRightService;
use Illuminate\Support\Facades\Session;

class AnnouncementController extends Controller
{
    public function __construct( UserRightService $userrightService
    )
    {
        $this->userrightService = $userrightService;
    }
    public function index(Request $request)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Announcements");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $announcements = Announcement::where('active', 1)->get();
        $announcements = json_encode($announcements);
        return view('announcement.index', compact('announcements'));

        //$announcements = Announcement::where('active', 1)->get();
        //return view('announcement.index', compact('announcements'));
    }
    public function getAnnouncements()
    {
        $announcements = Announcement::where('active', 1)->get();
        return $announcements;
    }
    public function create()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Announcements");
        if (!$rolesPermissions['create']) {
            abort(401);
        }
        return view('announcement.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'recipient' => 'required',
            'message' => 'required',

        ]);

        $created_at = \Carbon\Carbon::now();
        $recipients = "";
        foreach ($request->recipient as $rec) {
            if ($recipients == "") {
                $recipients = $rec['username'];
            } else {
                $recipients = $recipients . ',' . $rec['username'];
            }
        }
        //   dd($recipients);
        Announcement::create([
            'title' => $request->title,
            'range_from' => $request->date_from,
            'range_to' => $request->date_to,
            'recipient' => $recipients,
            'message' => $request->message,
            'created_at' => $created_at,
            'active' => 1
        ]);

        return redirect('/announcements/create')->with('success', 'Announcement has been saved!!');
    }
    public function edit($id)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Announcements");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $announcement = Announcement::where('id', $id)->first();
        // dd($announcement);
        return view('announcement.edit', compact('announcement'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'recipient' => 'required',
            'message' => 'required',

        ]);
        $recipients = "";
        foreach ($request->recipient as $rec) {
            if ($recipients == "") {
                $recipients = $rec['username'];
            } else {
                $recipients = $recipients . ',' . $rec['username'];
            }
        }
        $announcement = Announcement::find($request->id);

        $data = [
            'title' => $request->title,
            'range_from' => $request->date_from,
            'range_to' => $request->date_to,
            'recipient' => $recipients,
            'message' => $request->message,
        ];


        $announcement->update($data);
        return redirect()->route('announcements.index')->with('success', 'Announcement has been updated!!');
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $announcement = Announcement::find($request->id);

        $data = [
            'active' => 0,
        ];
        $announcement->update($data);
        return response()->json('Roles Sync Completed!');
        // return redirect()->route('announcements.index')->with('success', 'Announcement has been updated!!');        
    }
    public function recipients()
    {
        $users = User::where([['active', 1], ['username', '<>', 'ADMIN']])->get(['username'])->makeHidden(['approle_desc', 'status']);
        return $users;
    }
    public function getAnnouncementsPerUser()
    {
        $announcements = Announcement::whereRaw("active = 1 AND recIpient LIKE  '%" .  auth()->user()->username . "%' AND ISNULL(readBy, '') NOT LIKE '%" . auth()->user()->username . "%'")->get();

        return $announcements;
    }
    public function saveUserRead(Request $request)
    {
        $announcements = Announcement::whereRaw("active = 1 AND recIpient LIKE  '%" .  $request->user . "%' AND ISNULL(readBy, '') NOT LIKE '%" . $request->user . "%'")->get();

        foreach ($announcements as $announcement) {

            if ($announcement->readBy == null || $announcement->readBy == "") {
                $announcement->update(['readBy' => $request->user]);
            } else {
                $announcement->update(['readBy' => $announcement->readBy . ',' . $request->user]);
            }
        }
    }
    public function report()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Announcement Report");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('announcement.report');
    }
    public function getreport(Request $request)
    {
        $dateFrom = now()->toDateString();
        $dateTo = now()->toDateString();
        if (isset($request->dateFrom)) {
            $dateFrom = $request->dateFrom;
        }
        if (isset($request->dateTo)) {
            $dateTo = $request->dateTo;
        }
        $announcements = Announcement::where('active', 1)
            ->when(isset($dateTo), function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('range_from',  [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            })
            ->when(!isset($dateTo), function ($q) use ($dateFrom) {
                $q->whereDate('range_from', $dateFrom);
            })
            ->orderBy('range_from', 'desc')->get();

        return $announcements;
    }
}
