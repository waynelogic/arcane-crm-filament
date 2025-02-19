<?php namespace App\Service\EnterpriseExchange\Resources;

use App\Service\EnterpriseExchange\ImportModels;
class Catalog extends AbstractResource
{
    public function deactivate() : string
    {
        return $this->success();
    }

    public function parse() : bool
    {
        set_time_limit(900);

        $parsers = [
            'goods' => ImportModels\Products::class,
            'offers' => ImportModels\Offers::class,
            'prices' => ImportModels\Prices::class
        ];

        [$type] = explode('_', $this->fileName, 2) + [''];

        if (!isset($parsers[$type])) {
            return false;
        }

        (new $parsers[$type])->parse($this->filePath);

        return true;
    }
}
