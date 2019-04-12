<?php


namespace App\Twig;


use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ArrayToStringConversionExtension extends AbstractExtension
{
    private $request;

    /**
     * ArrayToStringConversionExtension constructor.
     * @param $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('convert_to_string', [$this, 'arrayToString']),
        ];
    }

    public function arrayToString(array $content, $separator = ",")
    {
        $toString = implode($separator, $content);

        return $toString;
    }
}
