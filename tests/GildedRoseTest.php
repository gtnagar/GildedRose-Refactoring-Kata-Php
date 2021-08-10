<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('fixme', $items[0]->name);
    }

    function updates_normal_items_before_sell_date()
    {
        $items = [new Item('foo', 10, 5)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(4, $items[0]->quality);
    }

    /** @test */
    function updates_normal_items_on_the_sell_date()
    {
        $items = [new Item('foo', 0, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(8, $items[0]->quality);
    }

    /** @test */
    function updates_normal_items_after_the_sell_date()
    {
        $items = [new Item('foo', -5, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();


        $this->assertEquals(-6, $items[0]->sell_in);
        $this->assertEquals(8, $items[0]->quality);
    }

    /** @test */
    function updates_normal_items_with_a_quality_of_0()
    {
        $items = [new Item('foo', 5, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(4, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_before_the_sell_date()
    {
        $items = [new Item('Aged Brie', 5, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(4, $items[0]->sell_in);
        $this->assertEquals(11, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_before_the_sell_date_with_maximum_quality()
    {

        $items = [new Item('Aged Brie', 5, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(4, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_on_the_sell_date()
    {
        $items = [new Item('Aged Brie', 0, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(12, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_on_the_sell_date_near_maximum_quality()
    {
        $items = [new Item('Aged Brie', 0, 49)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_on_the_sell_date_with_maximum_quality()
    {
        $items = [new Item('Aged Brie', 0, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_after_the_sell_date()
    {
        $items = [new Item('Aged Brie', -10, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-11, $items[0]->sell_in);
        $this->assertEquals(12, $items[0]->quality);
    }

    /** @test */
    function updates_brie_items_after_the_sell_date_with_maximum_quality()
    {
        $items = [new Item('Aged Brie', -10, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-11, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_sulfuras_items_before_the_sell_date()
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 10, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(10, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);
    }

    /** @test */
    function updates_sulfuras_items_on_the_sell_date()
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);
    }

    /** @test */
    function updates_sulfuras_items_after_the_sell_date()
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', -1, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);
    }

    /*
        "Backstage passes", like aged brie, increases in Quality as it's SellIn
        value approaches; Quality increases by 2 when there are 10 days or
        less and by 3 when there are 5 days or less but Quality drops to
        0 after the concert
     */

    /** @test */
    function updates_backstage_pass_items_long_before_the_sell_date()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            11,
            10
        )];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(10, $items[0]->sell_in);
        $this->assertEquals(11, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_close_to_the_sell_date()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            10,
            10
        )];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(12, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_close_to_the_sell_data_at_max_quality()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            10,
            50
        )];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_very_close_to_the_sell_date()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            5,
            10
        )];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(4, $items[0]->sell_in);
        $this->assertEquals(13, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_very_close_to_the_sell_date_at_max_quality()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            5,
            50
        )];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(4, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_with_one_day_left_to_sell()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            1,
            10
        )];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(13, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_with_one_day_left_to_sell_at_max_quality()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            1,
            50
        )];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(50, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_on_the_sell_date()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            0,
            10
        )];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /** @test */
    function updates_backstage_pass_items_after_the_sell_date()
    {
        $items = [new Item(
            'Backstage passes to a TAFKAL80ETC concert',
            -1,
            10
        )];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-2, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /** @test */
    function _updates_conjured_items_before_the_sell_date()
    {
        $items = [new Item('Conjured Mana Cake', 10, 10)];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(8, $items[0]->quality);
    }

    /** @test */
    function updates_conjured_items_at_zero_quality()
    {
        $items = [new Item('Conjured Mana Cake', 10, 0)];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /** @test */
    function updates_conjured_items_on_the_sell_date()
    {
        $items = [new Item('Conjured Mana Cake', 0, 10)];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(6, $items[0]->quality);
    }

    /** @test */
    function updates_conjured_items_on_the_sell_date_at_0_quality()
    {
        $items = [new Item('Conjured Mana Cake', 0, 0)];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /** @test */
    function updates_conjured_items_after_the_sell_date()
    {
        $items = [new Item('Conjured Mana Cake', -10, 10)];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-11, $items[0]->sell_in);
        $this->assertEquals(6, $items[0]->quality);
    }

    /** @test */
    function updates_conjured_items_after_the_sell_date_at_zero_quality()
    {
        $items = [new Item('Conjured Mana Cake', -10, 0)];

        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(-11, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }
}
