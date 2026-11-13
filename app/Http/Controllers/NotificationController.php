<?php

namespace App\Http\Controllers;

use App\Models\ArtworkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function getNotificationPerUser(Request $request){
    //     $getAtworkLogs = ArtworkLog::with(['user', 'file'])
    //     ->where('user_id' ,Auth::user()->emp_id)
    //     ->latest()
    //     ->take(15)
    //     ->get();
    //     $logs = $getAtworkLogs;
      
    //     // $unreadCount = ArtworkLog::where('status', NULL)->count(); //

    //     // Filter the collection based on the nested 'file' relationship status
    //     $unreadCount = $logs->filter(function ($log) {
    //         return $log->file && is_null($log->file->status);
    //     })->count();

    //     // 3. If AJAX submission, return JSON response with rendered partial HTML
    //     if ($request->ajax()) {
    //         $html = view('partials.artwork_logs', compact('logs', 'unreadCount'))->render();

    //         return response()->json([
    //             'success'     => 'RFA Link sent successfully!',
    //             'html'        => $html,
    //             'unreadCount' => $unreadCount,
    //         ]);
    //     }
    // }


public function getNotificationPerUser(Request $request)
{
      $getAtworkLogs = ArtworkLog::with(['user', 'file'])
    //   ->where('sent_to',Auth::user()->emp_id)
            ->latest()
            ->take(15)
            ->get();
    $logs = $getAtworkLogs;

    $unreadCount = $logs->filter(function ($log) {
        $status = $log->status ?? $log->file?->status;
        return strtolower((string)$status) !== 'opened';
    })->count();

    if ($request->ajax()) {
        // Pass $listOnly = true so Blade renders only the list items
        $html = view('partials.artwork_logs', [
            'logs'        => $logs,
            'unreadCount' => $unreadCount,
            'listOnly'    => true
        ])->render();

        return response()->json([
            'success'     => true,
            'html'        => $html,
            'unreadCount' => $unreadCount,
        ]);
    }

    return view('partials.artwork_logs', compact('logs', 'unreadCount'));
}

    

}
