<?php

use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    /**
     * @return bool
     */
    protected function executeInstall(): bool
    {

        if (!zen_page_key_exists('autoCategoryImages')) {
            zen_register_admin_page('autoCategoryImages', 'BOX_TOOLS_AUTO_CATEGORY_IMAGES', 'FILENAME_AUTO_CATEGORY_IMAGES', '', 'extras', 'Y');
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function executeUninstall(): bool
    {
        zen_deregister_admin_pages('autoCategoryImages');
        return true;
    }

}
