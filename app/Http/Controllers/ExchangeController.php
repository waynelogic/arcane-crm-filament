<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\EnterpriseExchange\Resources\Catalog;
class ExchangeController extends Controller
{
    public function __invoke(Request $request, Catalog $catalog)
    {
        $type = $_GET['type'] ?? 'catalog';
        $mode = $_GET['mode'] ?? 'checkauth';
        $object = match ($type) {
            'catalog' => $catalog,
            default => null,
        };
        if (!method_exists($object, $mode)) {
            throw new \Exception('Метод "' . $mode . '" не найден!');
        }
        return $object->$mode();
    }
}
