<?php

namespace Controllers;

use Framework\ControllerInterface;
use Framework\CSRFProtection;
use Framework\FlashMessages;
use Framework\ServiceContainer;
use Framework\UrlBuilder;
use function Framework\renderPage;

class PocketImportViewController implements ControllerInterface
{
    public function __invoke()
    {
        $url_builder = ServiceContainer::get(UrlBuilder::class);
        $flash = FlashMessages::pull();
        $csrf_token = CSRFProtection::generateToken();

        return renderPage('pocket-import', 'primary', compact('url_builder', 'flash', 'csrf_token'));
    }
}
