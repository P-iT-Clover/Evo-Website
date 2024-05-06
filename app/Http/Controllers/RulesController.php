<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleCreateRequest;
use App\Models\Rule;

class RulesController extends Controller
{
    public function showRules()
    {
        $rules = Rule::paginate(10);

        return view('rules', ['rules' => $rules]);
    }

    public function processRuleCreation(RuleCreateRequest $request)
    {
        Rule::create([
            'label' => $request->get('ruleLabel'),
            'description' => $request->get('ruleDescription')
        ]);

        return back()->with('success', 'Successfully created new rule!');
    }
}
