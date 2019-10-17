<?php
defined('TYPO3_MODE') or die();

$widgetRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\FriendsOfTYPO3\Dashboard\Registry\WidgetRegistry::class);
$widgetRegistry->registerWidget('cacheSettings', \ThomasK\TkCache\Widgets\CacheSettingsWidget::class);

$dashboardRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\FriendsOfTYPO3\Dashboard\Registry\DashboardRegistry::class);
$dashboardRegistry->registerDashboard('caches', 'LLL:EXT:tk_cache/Resources/Private/Language/locallang_be.xlf:dasboard.caches');

