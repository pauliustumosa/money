
Formatting
==========

Since version 3.0.0 you can format a Money object. This means you can turn the object in a human readable string. By
default the library contains two formatters. Most likely you will need the IntlMoneyFormatter. The second option is the
BitcoinSupportedMoneyFormatter. This formatter decorates the IntlMoneyFormatter. If none of the two is matching your
needs, you can write your own formatter by implementing the interface MoneyFormatter.

In order to use the IntlMoneyFormatter you will need the intl extension. Please find an example below.

.. code-block:: php
   
    <?php
    use Money\Currency;
    use Money\Formatter\IntlMoneyFormatter;
    use Money\Money;

    $money = new Money(100, new Currency('USD'));

    $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
    $moneyFormatter = new IntlMoneyFormatter($numberFormatter);

    echo $moneyFormatter->format($money); // prints $1.00