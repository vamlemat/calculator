<?php
/**
 * Upgrade to version 1.2.0
 * 
 * Changes:
 * - Spanish number format (comma decimals)
 * - New calculator icon logo
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_2_0($module)
{
    // No database changes required for this version
    // Only frontend improvements
    return true;
}
