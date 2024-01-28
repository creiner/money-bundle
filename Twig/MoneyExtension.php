<?php

declare(strict_types=1);

namespace JK\MoneyBundle\Twig;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * This class contains the configuration information for the bundle.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class MoneyExtension extends AbstractExtension
{
    final public const FORMAT_CURRENCY = true;
    final public const FORMAT_DECIMAL = false;
    final public const GROUPING_NONE = false;

    final public const GROUPING_USED = true;

    public function __construct(private $locale)
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('money', $this->moneyFilter(...)),
        ];
    }

    public function moneyFilter(Money $money, $scale = 2, $groupingUsed = self::GROUPING_USED, $format = self::FORMAT_CURRENCY)
    {
        $noFormatter = new \NumberFormatter($this->locale, self::FORMAT_CURRENCY === $format ? \NumberFormatter::CURRENCY : \NumberFormatter::DECIMAL);
        $noFormatter->setAttribute(\NumberFormatter::GROUPING_USED, (int) $groupingUsed);
        $noFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $scale);

        $intlFormatter = new IntlMoneyFormatter($noFormatter, new ISOCurrencies());

        // replace non-break spaces with ascii spaces
        return str_replace("\xc2\xa0", "\x20", $intlFormatter->format($money));
    }
}
