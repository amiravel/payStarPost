<?php


namespace App\Response;


use Illuminate\Auth\AuthenticationException;
use phpDocumentor\Reflection\Types\Self_;

class Status
{

    private const STATUSES = [
        200 => [
            'httpCode' => 200,
            'message' => 'OK',
            'hasError' => false
        ],
        400 => [
            'httpCode' => 400,
            'message' => 'Bad Request',
            'hasError' => true
        ],
        'AuthenticationException' => [
            'httpCode' => 400,
            'message' => 'authentication failed! please check your credentials.',
            'hasError' => true
        ],
        'UnauthorizedException' => [
            'httpCode' => 403,
            'message' => 'unauthorized.',
            'hasError' => true
        ],
        'ErrorException' => [
            'httpCode' => 500,
            'message' => 'internal server error.',
            'hasError' => true
        ],
        'ModelNotFoundException' => [
            'httpCode' => 404,
            'message' => 'Not Found',
            'hasError' => true
        ]

    ];

    /**
     * @param int $code
     * @return array
     */
    public static function get($code = 200)
    {

        return self::STATUSES[$code] ?? self::STATUSES[400];
    }

}
