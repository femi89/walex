<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegerTargetController extends Controller
{
    /**
    */
    public function generateIndex(Request $request) {
        $credential = $request->validate([
           'data' => ['array', 'required'],
            'target' => ['integer', 'required']
        ]);
        return;
    }
}
