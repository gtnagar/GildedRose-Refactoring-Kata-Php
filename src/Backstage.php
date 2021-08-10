<?php

namespace GildedRose;

use GildedRose\Item;
use GildedRose\ItemTick;

class Backstage implements ItemTick
{

    public static function tick(Item $item)
    {
        $item->sell_in--;
        $item->quality++;

        if ($item->sell_in < 10) {
            $item->quality++;
        }   

        if ($item->sell_in < 5) {
            $item->quality++;
        }

        if ($item->quality > 50) {
            $item->quality = 50;
        }
        
        if ($item->sell_in < 0) {
            $item->quality = 0;
        }

    }
}
