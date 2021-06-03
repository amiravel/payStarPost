<?php


namespace App\Response;


class Response
{

    public static function json($statusCode = 200, $data = [])
    {
        $response = Status::get($statusCode);

        if (!empty($data)){
            $response['data'] = $data;
        }

        return response($response, $response['httpCode']);
    }

}
