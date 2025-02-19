<?php namespace App\Service\EnterpriseExchange\ImportModels;

use XMLReader;
use SimpleXMLElement;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class Offers
{

    const EMPTY_PROPERTY = '00000000-0000-0000-0000-000000000000';

    public function parse($filePath) {
        $reader = new XMLReader();
        $reader->open(Storage::path($filePath));
        while ($reader->read()) {
            while ($reader->name === 'Предложение') {
                $xml = new SimpleXMLElement($reader->readOuterXML());
                $this->import_offer($xml);
                $reader->next('Предложение');
            }
        }
        $reader->close();

        return true;
    }

    private function import_offer(SimpleXMLElement $xml): void
    {
        $guid = (string) $xml->Ид;
        $product_guid = explode('#', $guid)[0];

        if ($product_guid == $guid) {
            return;
        }

        $obProduct = Product::where('external_id', $product_guid)->first();
        if (!$obProduct) {
            return;
        };

        $obOffer = (object) [
            'external_id' => $guid,
            'name' => (string) $xml->Наименование,
            'active' => true,
        ];

        $obProduct->addOffer($obOffer);
        $obProduct->save();
    }
}
