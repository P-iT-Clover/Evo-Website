<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffCreate;
use App\Http\Requests\StaffDelete;
use App\Http\Requests\StaffEdit;
use App\Models\Staff;

class StaffController extends Controller
{
    public function showStaff()
    {
        $staff = Staff::paginate(5);

        return view('staff', ['staff' => $staff]);
    }

    public function processStaffCreation(StaffCreate $request)
    {
        Staff::create([
            'discord' => $request->get('discord'),
            'role' => $request->get('role'),
            'image' => $request->get('image')
        ]);

        return back()->with('success', $request->get('discord') . ' created successfully!');
    }

    public function processStaffDelete(StaffDelete $request)
    {
        $staff = Staff::where('id', $request->get('staffID'))->firstOrFail();

        $staff->delete();

        return back()->with('success', 'Staff member successfully deleted!');
    }

    public function processStaffEdit(StaffEdit $request)
    {
        $staff = Staff::where('id', $request->get('staffID'))->firstOrFail();

        $staff->update([
            'discord' => $request->get('discord'),
            'role' => $request->get('role'),
            'image' => $request->get('image')
        ]);

        return back()->with('success', 'Successfully edit this staff member!');
    }
}
