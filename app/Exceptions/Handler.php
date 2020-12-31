<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException as ValidationException;
use Illuminate\Auth\Access\AuthorizationException as AccessDeniedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException as MethodNotAllowedHttpException;
use Throwable;
use Request;
use Illuminate\Auth\AuthenticationException;
use Response;
use Session;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {

        if ($exception   instanceof \PDOException) {
            Log::critical($exception->getMessage());
            return;
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        //return response()->json(['error'=>'something went wrong'], 401);
        if ($exception instanceof ValidationException)
        {
            $first_keys=($exception->errors());
            $first_key=reset($first_keys);
            $first_value=reset($first_key);
            return new JsonResponse(['message'=>$first_value,'status'=>false], 400);
        }
        if ($exception instanceof AccessDeniedException) {
            return response()->json([
                'message' => 'Access Denied','status'=>false
            ],401);
        }
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Model Not Found','status'=>false
            ],401);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Not Found','status'=>false
                ],401);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method Not Allowed','status'=>false
                ],401);
        }
        if ($exception instanceof Illuminate\Contracts\Debug\ExceptionHandler) {
            return response()->json([
                'message' => 'Controller May Not Found','status'=>false
                ],401);
        }
        if ($exception instanceof AuthenticationException ) {
            return response()->json([
                'message' => 'Unauthenticated','status'=>false, 'login'=>false
                ],401);
        }
        if ($exception instanceof TokenMismatchException){
            // Redirect to a form. Here is an example of how I handle mine
            Session::flash('message', "Oops! Seems you couldn't submit form for a long time. Please try again.");
            return redirect('login')->with('csrf_error',"Oops! Seems you couldn't submit form for a long time. Please try again.");
        }
        return parent::render($request, $exception);

    }


}
