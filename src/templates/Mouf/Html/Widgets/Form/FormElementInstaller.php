<?php
namespace Mouf\Html\Widgets\Form;

use Mouf\Installer\PackageInstallerInterface;
use Mouf\MoufManager;
use Mouf\Html\Renderer\RendererUtils;

/**
 * A logger class that writes messages into the php error_log.
 */
class FormElementInstaller implements PackageInstallerInterface {

    /**
     * (non-PHPdoc)
     * @see \Mouf\Installer\PackageInstallerInterface::install()
     */
    public static function install(MoufManager $moufManager) {
               	// Let's create the renderer
		RendererUtils::createPackageRenderer($moufManager, "mouf/html.widgets.form-elements");

        // Let's rewrite the MoufComponents.php file to save the component
        $moufManager->rewriteMouf();
    }
}
