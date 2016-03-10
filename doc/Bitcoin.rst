
Bitcoin
=======

If you want to work with the Bitcoin currency, Money has support for this since version 3.0.0. You can construct a
currency object by using the code XBT. For Bitcoin there is a formatter and a parser available. Please be aware that
using the intl extension can give different results per system.

Please see example below how to use the Bitcoin currency.

.. code-block:: php
   
    <?php
    // construct bitcoin
    $money = new Money(100000, new Currency('XBT'));

    // format bitcoin
    $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
    $intlFormatter = new IntlMoneyFormatter($numberFormatter);

    $formatter = new BitcoinSupportedMoneyFormatter($intlFormatter, 2);
    echo $formatter->format($money); // prints Ƀ1000.00

    // parse bitcoin
    $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
    $intlParser = new IntlMoneyParser($formatter);

    $parser = new BitcoinSupportedMoneyParser($intlParser, 2);
    $money = $parser->parse("\0xC9\0x831000.00", 'USD');
    echo $money->getAmount(); // prints 100000

