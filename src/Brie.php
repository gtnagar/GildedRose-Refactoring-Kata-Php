<?php

namespace GildedRose;

use GildedRose\Item;
use GildedRose\ItemTick;

class Brie implements ItemTick
{
    public static function tick(Item $item)
    {
        $item->sell_in--;
        $item->quality++;

        if ($item->sell_in <= 0) {
            $item->quality++;
        }

        if ($item->quality > 50) {
            $item->quality = 50;
        }
    }
}
