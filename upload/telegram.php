<?php
/**
 * This file is a part of [Telegram] Core addon.
 *
 * Distributed by GNU GPL v3.0 license.
 */

$dir = __DIR__;
require ($dir . '/src/XF.php');

XF::start($dir);
$app = XF::setupApp('XF\Pub\App');

$hookClass = $app->extendClass('Kruzya\\Telegram\\WebHook');
$hook = new $hookClass($app);
$hook->handleWebhook($app->request());
