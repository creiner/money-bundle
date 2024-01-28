<?php declare(strict_types=1);

namespace JK\MoneyBundle\Form\DataTransformer;

use Money\Money;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Money\Exception\ParserException;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currencies;
use Money\Currency;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;

/**
 * Transforms between a normalized format and a localized money string.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class MoneyToLocalizedStringTransformer implements DataTransformerInterface
{
    private readonly Currencies $currencies;

    private readonly NumberToLocalizedStringTransformer $numberToLocalizedStringTransformer;

    private readonly DecimalMoneyFormatter $decimalMoneyFormatter;

    private readonly DecimalMoneyParser $decimalMoneyParser;

    public function __construct(private readonly Currency $currency, ?int $scale = null, ?bool $grouping = false, ?Currencies $currencies = null)
    {
        $this->currencies = $currencies ?: new ISOCurrencies();
        $this->numberToLocalizedStringTransformer = new NumberToLocalizedStringTransformer($scale, $grouping);
        $this->decimalMoneyFormatter = new DecimalMoneyFormatter($this->currencies);
        $this->decimalMoneyParser = new DecimalMoneyParser($this->currencies);
    }

    /**
     * Transforms a normalized format into a localized money string.
     *
     * @param \Money\Money $value Money object
     *
     * @return string Localized money string
     *
     * @throws TransformationFailedException If the given value is not numeric or
     *                                       if the value can not be transformed.
     */
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }


        return $this->numberToLocalizedStringTransformer->transform($this->decimalMoneyFormatter->format($value));
    }

    /**
     * Transforms a localized money string into a normalized format.
     *
     * @param string $value Localized money string
     *
     * @return \Money\Money Money object
     *
     * @throws TransformationFailedException If the given value is not a string
     *                                       or if the value can not be transformed.
     */
    public function reverseTransform(mixed $value): Money
    {
        $value = $this->numberToLocalizedStringTransformer->reverseTransform($value);
        try {
            return $this->decimalMoneyParser->parse(sprintf('%.53f', $value), $this->currency);
        } catch (ParserException $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}
