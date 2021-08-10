<?php
namespace GildedRose;
use GildedRose\Item;

class Conjured implements ItemTick
{
    public static function tick(Item $item)
    {
        $item->sell_in--;
        $item->quality -= 2;

        if ($item->sell_in <= 0) {
            $item->quality -= 2;
        }

        if ($item->quality < 0) {
            $item->quality = 0;
        }
    }
}
