<?php

namespace App\Models;

use App\Enums\ProductType;
use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends FileModel
{
    use HasExternalId, Sluggable;

    protected $slugs = [
        'slug' => 'name'
    ];
    protected $casts = [
        'price' => 'float',
        'offers' => 'collection',
        'active' => 'boolean',
        'type' => ProductType::class
    ];


    public function addOffer($offer): static
    {
        $colOffers = $this->offers ?? collect();
        if ($colOffers->contains('external_id', $offer->external_id)) {
            $obOffer = (object) $colOffers->where('external_id', $offer->external_id)->first();
        } else {
            $colOffers->push($offer);
        }
        $this->offers = $colOffers;

        return $this;
    }

    public function setOfferPrice($offer, $price)
    {
        $offer = $this->offers->where('external_id', $offer)->first();
        $offer['price'] = $price;
        $this->offers = $this->offers->where('external_id', '!=', $offer['external_id'])->push($offer);
    }
}
