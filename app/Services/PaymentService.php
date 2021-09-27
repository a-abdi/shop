<?php

namespace App\Services;

use App\Services\MainService;

class PaymentService extends MainService
{
    /**
     * Create data for update.
     *
     * @param string
     * @param string
     * @param int
     * @return array
     */
    public function dataUpdate(string $trackId, string $cardNo, int $statusCode)
    {
        return [
            'track_id' => $trackId,
            'card_no' => $cardNo,
            'status_code' => $statusCode,
        ];
    }
}