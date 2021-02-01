<?php

namespace App\Models;

class Response
{
    protected $message = [
        '00' => "Success",
        '01' => "User not found",
        '02' => "Product not found",
        '04' => "Product quantity exceeds available stock",
        '05' => "Cart not found",
        '06' => "No product found in cart",
        '07' => "Bill already paid",
        '99' => "An error has occurred"
    ];
    
    public function get_response($code, $data = null) {
        if(!array_key_exists($code, $this->message)) $code = '99';

        $result = [
            'responseCode' => $code,
            'responseMessage' => $this->message[$code],
        ];

        if($code == '00') $result['data'] = $data;

        return $result;
    }
}
