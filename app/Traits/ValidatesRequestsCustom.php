<?php

namespace App\Traits;

use Illuminate\Foundation\Validation\ValidatesRequests as BaseValidateRequests;
use Illuminate\Http\Request;

trait ValidatesRequestsCustom
{
    use BaseValidateRequests;
    
    public function validateRed(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $message = "";
            foreach ($validator->errors()->all() as $val) {
                $message .= '<span style="color:red;">'.$val.'</span></br>';
            }
            return $message;
        }
        
    }
    
}