<?php

namespace Smartwave\Porto\Api\ViewModel;

interface CssConfigInterface
{
    public const GENERATED_CSS_FOLDER = 'porto/configed_css/';
    /** @phpstan-ignore-next-line */
    public const GENERATED_CSS_DIR = BP . 'pub/media/' . self::GENERATED_CSS_FOLDER;
}
