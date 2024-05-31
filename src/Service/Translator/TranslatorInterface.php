<?php

namespace App\Service\Translator;

interface TranslatorInterface
{
    public function translate(string $text, string $targetLang): string;
}