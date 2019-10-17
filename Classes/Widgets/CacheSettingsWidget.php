<?php

namespace ThomasK\TkCache\Widgets;

use FriendsOfTYPO3\Dashboard\Widgets\AbstractListWidget;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class CacheSettingsWidget extends AbstractListWidget
{
    public function __construct()
    {
        AbstractListWidget::__construct();
        $this->width = 4;
        $this->height = 4;
        $this->title = 'Cache Settings';
        $this->description = 'LLL:EXT:dashboard/Resources/Private/Language/locallang.xlf:widgets.lastLogins.description';
        $this->iconIdentifier = 'dashboard-bars';
    }

    /**
     * @var string
     */
    protected $templateName = 'CacheSettings';

    /**
     * Sets up the Fluid View.
     */
    protected function initializeView(): void
    {
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->setTemplate($this->templateName);
        $this->view->setTemplateRootPaths(['EXT:tk_cache/Resources/Private/Templates/Widgets']);
        $this->view->setLayoutRootPaths(['EXT:dashboard/Resources/Private/Layouts/Widgets']);
    }

    public function prepareData(): void
    {
        $cacheTypes = [
            'cache_hash',
            'cache_pages',
            'cache_pagesection',
            'cache_imagesizes',
            'cache_rootline',
        ];

        $cacheConfigurations = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];

        foreach ($cacheConfigurations as $cache => $settings) {
            if (in_array($cache, $cacheTypes, true)) {
                $this->items[] = [
                   'name' => $cache,
                   'type' => $settings['backend'],
                ];
            }
        }

        $this->totalItems = count($this->items);

    }
}