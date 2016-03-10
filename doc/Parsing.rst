
Formatting
==========

Since version 3.0.0 you can parse a string into a Money object. Before the 3.x release there was a stringToUnits method
available inside the Money class. That has been moved into the StringToUnitsParser. By default the library contains
three parsers. Most likely you will need the StringToUnitsParser or the IntlMoneyFormatter. The last option is the
BitcoinSupportedMoneyParser. If none of the three is matching your needs, you can write your own parser by implementing
the interface MoneyParser.

Please find an example of the StringToUnitsParser below. In order to use the IntlMoneyParser you will need the intl
extension. Please find an example below.

.. code-block:: php
   
    <?php
    use Money\Parser\StringToUnitsParser;
    use Money\Money;

    $parser = new StringToUnitsParser();
    $money = $parser->parse('1000', 'USD');
    echo $money->getAmount(); // prints 100000