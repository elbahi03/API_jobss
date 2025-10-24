<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 
*@OA\Info(
*version="1.0.0",
*title="Job Portal API Documentation",
*description="API for managing Job Offers",
*@OA\Contact(
*email="support@musicbox.com"
*)
*)
*@OA\Server(
*url=L5_SWAGGER_CONST_HOST,
*description="Job Portal API Server"
*)
*@OA\SecurityScheme(
*securityScheme="bearerAuth",
*in="header",
*name="Bearer Token Authentication",
*type="http",
*scheme="Bearer",
*bearerFormat="Sanctum Token",
*)
*/

class Swager extends Controller
{
    //
}
