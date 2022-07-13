<?php

namespace App\Http\Controllers;

use App\Requisition;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    public function mark_as_read(Request $request)
    {
        $requisition_id = $request->input('requisition_id');
        Requisition::find($requisition_id)->unread_notifications->markAsRead();
        return response()->noContent();
    }
}
