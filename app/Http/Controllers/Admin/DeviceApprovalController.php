<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceAuthorization;
use Illuminate\Http\Request;

class DeviceApprovalController extends Controller
{
    public function index()
    {
        $allDevices = DeviceAuthorization::with('user')->orderBy('is_approved')->get();

        return view('admin.device-approvals', compact('allDevices'));
    }
    

    public function approve($id)
    {
        $device = DeviceAuthorization::findOrFail($id);
        $device->update(['is_approved' => true]);

        return back()->with('success', 'Device has been approved successfully! The user can now log in.');
    }

    public function revoke($id)
    {
        $device = DeviceAuthorization::findOrFail($id);
        $device->delete();

        return back()->with('success', 'Device access has been revoked successfully!');
    }
}