<?php

namespace App\Http\Controllers;

use App\Enums\ConfigEnum;
use App\Models\ConfigTitle;
use Illuminate\Http\Request;

class TermsConditionsController extends Controller
{
    /**
     * Display the terms and conditions page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $content = ConfigTitle::with(['translations'])
            ->where('key', ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS)
            ->first();

        return view('pages.terms-conditions', [
            'content' => $content,
        ]);
    }
}
