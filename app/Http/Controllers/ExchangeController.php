<?php namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Service\EnterpriseExchange\Resources\Sale;
use App\Service\EnterpriseExchange\Resources\Catalog;
class ExchangeController extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request, Catalog $catalog, Sale $sale)
    {
        $type = $_GET['type'] ?? 'catalog';
        $mode = $_GET['mode'] ?? 'checkauth';
        $object = match ($type) {
            'catalog' => $catalog,
            'sale' => $sale,
            default => null,
        };
        if (!method_exists($object, $mode)) {
            throw new Exception(`Метод ${$mode} не найден!`);
        }
        return $object->$mode();
    }
}
