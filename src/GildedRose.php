<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Brie;
use GildedRose\Item;
use GildedRose\Normal;
use GildedRose\Conjured;
use GildedRose\Sulfuras;
use GildedRose\Backstage;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;
    private static $ITEM_LIST = array(
        'Aged Brie' => Brie::class,
        'Backstage passes to a TAFKAL80ETC concert' => Backstage::class,
        'Conjured Mana Cake' => Conjured::class,
        'Sulfuras, Hand of Ragnaros' => Sulfuras::class
    );

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($this->isSpecializedItem($item)) {
                self::$ITEM_LIST[$item->name]::tick($item);
            } else {
                Normal::tick($item);
            }
        }
    }

    private function isSpecializedItem($item)
    {
        return array_key_exists($item->name, self::$ITEM_LIST);
    }
}

interface ItemTick
{
    public static function tick(Item $item);
}
