<?php
namespace Tektonik\Plates\Extension;

use Tektonik\Plates\Engine;

/**
 * A common interface for extensions.
 */
interface ExtensionInterface
{
    public function register(Engine $engine);
}
