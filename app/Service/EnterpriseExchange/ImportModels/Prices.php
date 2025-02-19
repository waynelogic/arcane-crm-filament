<?php namespace App\Service\EnterpriseExchange\ImportModels;


use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use Waynelogic\Emporium\Models\Offer;
use Waynelogic\Emporium\Models\PriceType;
use XMLReader;

class Prices
{
    public array $priceTypes;

    public function parse($filePath) {
        $reader = new XMLReader();
        $reader->open(Storage::path($filePath));
        while ($reader->read()) {
            while ($reader->name === 'Предложение') {
                $xml = new SimpleXMLElement($reader->readOuterXML());
                $this->import_price($xml);
                $reader->next('Предложение');
            }
        }
        return true;
    }

    private function import_price(SimpleXMLElement $obItem) : void
    {
        $guid = (string) $obItem->Ид;
        $product_guid = explode('#', $guid)[0];
        $obProduct = Product::where('external_id', $product_guid)->first();
        $fPrice = (float) $obItem->Цены->Цена->ЦенаЗаЕдиницу;
        if ($product_guid == $guid) {
            $obProduct->price = $fPrice;
        } else {
            $obProduct->setOfferPrice($guid, $fPrice);
        }
        $obProduct->save();
    }
}
