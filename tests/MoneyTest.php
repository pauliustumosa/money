<?php

namespace Tests\Money;

use Money\Currency;
use Money\Money;

final class MoneyTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryMethods()
    {
        $this->assertEquals(
            Money::EUR(25),
            Money::EUR(10)->add(Money::EUR(15))
        );
        $this->assertEquals(
            Money::USD(25),
            Money::USD(10)->add(Money::USD(15))
        );
    }

    public function testGetters()
    {
        $m = new Money(100, $euro = new Currency('EUR'));
        $this->assertEquals(100, $m->getAmount());
        $this->assertEquals($euro, $m->getCurrency());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDecimalsThrowException()
    {
        new Money(0.01, new Currency('EUR'));
    }

    public function testEquality()
    {
        $m1 = new Money(100, new Currency('EUR'));
        $m2 = new Money(100, new Currency('EUR'));
        $m3 = new Money(100, new Currency('USD'));
        $m4 = new Money(50, new Currency('EUR'));
        $m5 = new Money('100', new Currency('EUR'));

        $this->assertTrue($m1->equals($m2));
        $this->assertFalse($m1->equals($m3));
        $this->assertFalse($m1->equals($m4));
        $this->assertTrue($m1->equals($m5));
    }

    public function testAddition()
    {
        $m1 = new Money(100, new Currency('EUR'));
        $m2 = new Money(100, new Currency('EUR'));
        $sum = $m1->add($m2);
        $expected = new Money(200, new Currency('EUR'));

        $this->assertEquals($expected, $sum);

        // Should return a new instance
        $this->assertNotSame($sum, $m1);
        $this->assertNotSame($sum, $m2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDifferentCurrenciesCannotBeAdded()
    {
        $m1 = new Money(100, new Currency('EUR'));
        $m2 = new Money(100, new Currency('USD'));
        $m1->add($m2);
    }

    public function testSubtraction()
    {
        $m1 = new Money(100, new Currency('EUR'));
        $m2 = new Money(200, new Currency('EUR'));
        $diff = $m1->subtract($m2);
        $expected = new Money(-100, new Currency('EUR'));

        $this->assertEquals($expected, $diff);

        // Should return a new instance
        $this->assertNotSame($diff, $m1);
        $this->assertNotSame($diff, $m2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDifferentCurrenciesCannotBeSubtracted()
    {
        $m1 = new Money(100, new Currency('EUR'));
        $m2 = new Money(100, new Currency('USD'));
        $m1->subtract($m2);
    }

    public function testMultiplication()
    {
        $m = new Money(1, new Currency('EUR'));
        $this->assertEquals(
            new Money(2, new Currency('EUR')),
            $m->multiply(1.5)
        );
        $this->assertEquals(
            new Money(1, new Currency('EUR')),
            $m->multiply(1.5, Money::ROUND_HALF_DOWN)
        );

        $this->assertNotSame($m, $m->multiply(2));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMultiplicationOperand()
    {
        $m = new Money(1, new Currency('EUR'));
        $m->multiply('operand');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRoundingMode()
    {
        $m = new Money(1, new Currency('EUR'));
        $m->multiply(1.2345, 'ROUNDING_MODE');
    }

    public function testDivision()
    {
        $m = new Money(10, new Currency('EUR'));
        $this->assertEquals(
            new Money(3, new Currency('EUR')),
            $m->divide(3)
        );
        $this->assertEquals(
            new Money(2, new Currency('EUR')),
            $m->divide(4, Money::ROUND_HALF_EVEN)
        );
        $this->assertEquals(
            new Money(3, new Currency('EUR')),
            $m->divide(3, Money::ROUND_HALF_ODD)
        );
        $this->assertEquals(
            new Money(4, new Currency('EUR')),
            $m->divide(3.3, Money::ROUND_UP)
        );
        $this->assertEquals(
            new Money(5, new Currency('EUR')),
            $m->divide(1.8, Money::ROUND_DOWN)
        );

        $this->assertNotSame($m, $m->divide(2));
    }

    public function testDivisionByZero()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $m = new Money(10, new Currency('EUR'));
        $m->divide(0);
    }

    public function testComparison()
    {
        $euro1 = new Money(1, new Currency('EUR'));
        $euro2 = new Money(2, new Currency('EUR'));
        $euro3 = new Money(3, new Currency('EUR'));
        $usd = new Money(1, new Currency('USD'));

        $this->assertTrue($euro2->greaterThan($euro1));
        $this->assertFalse($euro1->greaterThan($euro2));
        $this->assertTrue($euro1->lessThan($euro2));
        $this->assertFalse($euro2->lessThan($euro1));

        $this->assertTrue($euro2->greaterThanOrEqual($euro1));
        $this->assertTrue($euro2->greaterThanOrEqual($euro2));
        $this->assertFalse($euro2->greaterThanOrEqual($euro3));

        $this->assertFalse($euro2->lessThanOrEqual($euro1));
        $this->assertTrue($euro2->lessThanOrEqual($euro2));
        $this->assertTrue($euro2->lessThanOrEqual($euro3));

        $this->assertEquals(-1, $euro1->compare($euro2));
        $this->assertEquals(1, $euro2->compare($euro1));
        $this->assertEquals(0, $euro1->compare($euro1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDifferentCurrenciesCannotBeCompared()
    {
        Money::EUR(1)->compare(Money::USD(1));
    }

    public function testAllocation()
    {
        $m = new Money(100, new Currency('EUR'));
        list($part1, $part2, $part3) = $m->allocate([1, 1, 1]);
        $this->assertEquals(new Money(34, new Currency('EUR')), $part1);
        $this->assertEquals(new Money(33, new Currency('EUR')), $part2);
        $this->assertEquals(new Money(33, new Currency('EUR')), $part3);

        $m = new Money(101, new Currency('EUR'));
        list($part1, $part2, $part3) = $m->allocate([1, 1, 1]);
        $this->assertEquals(new Money(34, new Currency('EUR')), $part1);
        $this->assertEquals(new Money(34, new Currency('EUR')), $part2);
        $this->assertEquals(new Money(33, new Currency('EUR')), $part3);
    }

    public function testAllocationOrderIsImportant()
    {
        $m = new Money(5, new Currency('EUR'));
        list($part1, $part2) = $m->allocate([3, 7]);
        $this->assertEquals(new Money(2, new Currency('EUR')), $part1);
        $this->assertEquals(new Money(3, new Currency('EUR')), $part2);

        $m = new Money(5, new Currency('EUR'));
        list($part1, $part2) = $m->allocate([7, 3]);
        $this->assertEquals(new Money(4, new Currency('EUR')), $part1);
        $this->assertEquals(new Money(1, new Currency('EUR')), $part2);
    }

    public function testAllocationTo()
    {
        $m = new Money(15, new Currency('EUR'));
        list($part1, $part2) = $m->allocateTo(2);
        $this->assertEquals(new Money(8, new Currency('EUR')), $part1);
        $this->assertEquals(new Money(7, new Currency('EUR')), $part2);

        $m = new Money(10, new Currency('EUR'));
        list($part1, $part2) = $m->allocateTo(2);
        $this->assertEquals(new Money(5, new Currency('EUR')), $part1);
        $this->assertEquals(new Money(5, new Currency('EUR')), $part2);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Number of targets must be an integer
     */
    public function testAllocationToInvalidTargets()
    {
        $m = new Money(15, new Currency('EUR'));
        $m->allocateTo('target');
    }

    public function testComparators()
    {
        $this->assertTrue(Money::EUR(0)->isZero());
        $this->assertTrue(Money::EUR('0')->isZero());
        $this->assertTrue(Money::EUR(-1)->isNegative());
        $this->assertTrue(Money::EUR('-1')->isNegative());
        $this->assertTrue(Money::EUR(1)->isPositive());
        $this->assertTrue(Money::EUR('1')->isPositive());
        $this->assertFalse(Money::EUR(1)->isZero());
        $this->assertFalse(Money::EUR(1)->isNegative());
        $this->assertFalse(Money::EUR(-1)->isPositive());
    }

    public function testJsonEncoding()
    {
        $this->assertEquals(
            '{"amount":"350","currency":"USD"}',
            json_encode(Money::USD(350))
        );
    }
}
