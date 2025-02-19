<?php namespace App\Service\EnterpriseExchange\ImportModels;

// Laravel
use App\Enums\ProductType;
use App\Models\Product;
use XMLReader;
use SimpleXMLElement;
use Exception;
use Illuminate\Support\Facades\Storage;

class Products
{
    const EMPTY_PROPERTY = '00000000-0000-0000-0000-000000000000';
    public array $arCategories;
    public $arProductTypes;
    public array $arUnits;
    public array $arBrands;

    public $obCurrentType;

    /**
     * @throws Exception
     */
    public function parse($filePath): bool
    {
        $reader = new XMLReader();
        $reader->open(Storage::path($filePath));
        while ($reader->read()) {
            while ($reader->name === 'Товар') {
                $xml = new SimpleXMLElement($reader->readOuterXML());
                $this->import_product($xml);
                $reader->next('Товар');
            }
        }
        $reader->close();

        return true;
    }

    private function import_product(SimpleXMLElement $xml): void
    {
        $guid = (string) $xml->Ид;
        $obProduct = Product::firstOrNew(['external_id' => $guid]);
        $data = [
            'external_id' => $guid,
            'name' => (string) $xml->Наименование,
            'description' => (string) $xml->Описание,
            'sku' => (string) $xml->Артикул,
            'active' => true,
        ];
        $data = array_merge($data, $this->additional_data($xml->ЗначенияРеквизитов));
        $obProduct->fill($data);
        $obProduct->save();
        if (isset($xml->Картинка)) {
            foreach ($xml->Картинка as $sImage) {
                $path = 'exchange' . DIRECTORY_SEPARATOR . $sImage;
                if (Storage::exists($path)) {
                    $name = basename($sImage);
                    if ($obProduct->getFirstMedia('product-images', ['file_name' => $name])) {
                        continue;
                    }
                    $obProduct->addMedia(Storage::path($path))
                        ->usingFileName($name)
                        ->toMediaCollection('product-images');
                }
            }
        }
    }

    private function additional_data(SimpleXMLElement $arItems): array
    {
        $data = [];
        foreach ($arItems->ЗначениеРеквизита as $obItem) {
            $name = (string) $obItem->Наименование;
            $value = (string) $obItem->Значение;
            if (empty($value)) {
                continue;
            }
            if ($name == 'Код') {
                $data['code'] = $value;
            }
            if ($name === 'ТипНоменклатуры') {
                $data['type'] = $this->getType($value);
            }
        }
        return $data;
    }

    private function getType(string $value)
    {
        return match ($value) {
            'Товар' => ProductType::PRODUCT,
            'Услуга' => ProductType::SERVICE,
            default => null
        };
    }


}
