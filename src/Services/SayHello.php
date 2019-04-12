<?php


namespace App\Services;


use Symfony\Contracts\Translation\TranslatorInterface;

class SayHello
{
    public const MESSAGE = 'Symfony is Great !';

    private $translator;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function sayHello(): string
    {
        return $this->translator->trans(self::MESSAGE);
    }
}
