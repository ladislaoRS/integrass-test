<?php

namespace App\Http\Controllers;

// use App\User;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 *
 */
class ApiBaseController extends Controller
{

    /**
     * undocumented class variable
     *
     * @var string
     **/
    protected $statusCode = BaseResponse::HTTP_OK;


    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param mixed $data
     * @param array $headers
     *
     * @return mixed
     **/
    protected function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param string $message
     *
     * @return mixed
     **/
    protected function respondCreated($message = "Succesfully created")
    {
        return $this->setStatusCode(BaseResponse::HTTP_CREATED)->respond([
            'message' => $message
        ]);
    }
}
