<?php

namespace app\traits;

use think\facade\Log;

trait LogTrait
{
    public function log(array $data, string $name = 'info',$channel='file', string $func = 'record')
    {
        $data['request_id'] = REQUEST_ID;
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        return Log::channel($channel)->$func($jsonData, $name);
    }

}