<?php

namespace App\Http\Controllers;

use App\Traits\APIResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use APIResponse;

    public function test(Request $request)
    {
        $data = ['1', '2', '3'];
        $entry = ['first name', 'last name', 'email'];

        return $this->responseSuccess('thêm thành công',
            [
                'data' => $data,
                'entry' => $entry,
            ]);
    }

    public function testMail()
    {
        return view('emails.test2');
    }
}
