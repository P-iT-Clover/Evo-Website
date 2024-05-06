<?php

namespace App\Http\Controllers;

use App\Http\Requests\FAQCreate;
use App\Models\FAQ;

class FaqController extends Controller
{
    public function showFaq()
    {
        $faqs = FAQ::paginate(10);

        return view("faq", ['faqs' => $faqs]);
    }

    public function processFAQCreation(FAQCreate $request)
    {
        FAQ::create([
            'label' => $request->get('faqLabel'),
            'answer' => $request->get('faqAnswer')
        ]);

        return back()->with('success', 'Successfully created new FAQ!');
    }
}
