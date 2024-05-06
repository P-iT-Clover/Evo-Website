<?php

namespace App\Http\Controllers;

use App\Http\Requests\SponsorCreate;
use App\Http\Requests\SponsorDelete;
use App\Http\Requests\SponsorEdit;
use App\Models\Sponsor;

class SponsorsController extends Controller
{
    public function showSponsors()
    {
        $sponsors = Sponsor::paginate(5);

        return view('sponsors', ['sponsors' => $sponsors]);
    }

    public function processSponsorCreation(SponsorCreate $request)
    {
        Sponsor::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'image' => $request->get('image')
        ]);

        return back()->with('success', $request->get('name') . ' created successfully!');
    }

    public function processSponsorDelete(SponsorDelete $request)
    {
        $sponsor = Sponsor::where('id', $request->get('sponsorID'))->firstOrFail();

        $sponsor->delete();

        return back()->with('success', 'Sponsor successfully deleted!');
    }

    public function processSponsorEdit(SponsorEdit $request)
    {
        $sponsor = Sponsor::where('id', $request->get('sponsorID'))->firstOrFail();

        $sponsor->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'image' => $request->get('image')
        ]);

        return back()->with('success', 'Successfully edit this sponsor!');
    }
}
