<?php

namespace App\Traits;

trait ApiResponser{

	/**
     * Send success response json.
     *
	 * @param object $data
	 * @param string $message
	 * @param int $code
     * @return array
     */
    protected function successResponse($data = null, $message = "success", $code = 200)
	{
		return response()->json([
			'status' => 'success', 
			'message' => $message, 
			'data' => $data,
		], $code);
	}

	/**
     * Send error response json.
     *
	 * @param string $message
	 * @param int $code
     * @return array
     */
	protected function errorResponse($message = null, $code)
	{
		return response()->json([
			'status' => 'error',
			'message' => $message,
		], $code);
	}
}