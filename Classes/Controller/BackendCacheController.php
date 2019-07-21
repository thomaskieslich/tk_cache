<?php

namespace ThomasK\TkCache\Controller;

use ThomasK\TkCache\Utility\ApcuUtility;
use ThomasK\TkCache\Utility\OpcUtility;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Menu\Menu;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class BackendApcController
 *
 * @package Lavitto\ApcManager\Controller
 */
class BackendCacheController extends ActionController
{

    /**
     * The module menu items array. Each key represents a key for which values can range between the items in the array of that key.
     * Written by client classes.
     *
     * @var array
     */
    protected $MOD_MENU = [
        'function' => []
    ];

    /**
     * Default View Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * APC Overview
     */
    public function indexAction(): void
    {
        $this->registerDocheaderButtons();
        $this->generateMenu();

        $this->view->getModuleTemplate()->setModuleName($this->request->getPluginName() . '_' . $this->request->getControllerName());
        if (ApcuUtility::isApcuEnabled() === true) {
            $this->view->getModuleTemplate()->getPageRenderer()->addCssInlineBlock('TkCache',
                '.module-body{padding:65px 0 0!important;overflow-y:hidden}');
            $directoryPath = ExtensionManagementUtility::extPath('tk_cache') . 'Resources/Public/Php';
            $apcUrl = PathUtility::getAbsoluteWebPath(PathUtility::getRelativePathTo($directoryPath) . 'apc.php');
            $this->view->assignMultiple([
                'apcEnabled' => true,
                'apcUrl' => $apcUrl
            ]);
        }
    }

    /**
     * Opcache Overview
     */
    public function opcAction(): void
    {
        $this->registerDocheaderButtons();
        $this->generateMenu();

        //https://haydenjames.io/php-performance-opcache-control-panels/

        $this->view->getModuleTemplate()->setModuleName($this->request->getPluginName() . '_' . $this->request->getControllerName());
        if (OpcUtility::isOpcacheEnabled() === true) {
            $this->view->getModuleTemplate()->getPageRenderer()->addCssInlineBlock('TkCache',
                '.module-body{padding:65px 0 0!important;overflow-y:hidden}');
            $directoryPath = ExtensionManagementUtility::extPath('tk_cache') . 'Resources/Public/Php';
            $opcUrl = PathUtility::getAbsoluteWebPath(PathUtility::getRelativePathTo($directoryPath) . 'opc.php');
            $this->view->assignMultiple([
                'opcEnabled' => true,
                'opcUrl' => $opcUrl
            ]);
        }
    }

    /**
     * Configuration
     */
    public function configAction(): void
    {
        $this->registerDocheaderButtons();
        $this->generateMenu();
        $this->view->assignMultiple([
            'cacheBackends' => ApcuUtility::getCacheConfiguration(),
            'apcEnabled' => ApcuUtility::isApcEnabled()
        ]);
    }

    /**
     * Generates the menu-selector
     */
    protected function generateMenu(): void
    {
        /** @var Menu $menu */
        $menu = $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('WebFuncJumpMenu');
        foreach (['index', 'opc'] as $action) {
            $item = $menu->makeMenuItem()
                ->setHref($this->getControllerContext()->getUriBuilder()->uriFor($action))
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:tk_cache/Resources/Private/Language/locallang_be.xlf:module.action.' . $action));
            if ($this->getControllerContext()->getRequest()->getControllerActionName() === $action) {
                $item->setActive(true);
            }
            $menu->addMenuItem($item);
        }
        $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
    }

    /**
     * Register document header buttons
     */
    protected function registerDocheaderButtons(): void
    {
        /** @var ButtonBar $buttonBar */
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();
        $currentRequest = $this->request;
        $moduleName = $currentRequest->getPluginName();
        $getVars = $this->request->getArguments();

        // Reload
        $reloadButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($reloadButton, ButtonBar::BUTTON_POSITION_RIGHT);

        // Shortcut
        $mayMakeShortcut = $this->getBackendUser()->mayMakeShortcut();
        if ($mayMakeShortcut) {
            $extensionName = $currentRequest->getControllerExtensionName();
            if (count($getVars) === 0) {
                $modulePrefix = strtolower('tx_' . $extensionName . '_' . $moduleName);
                $getVars = ['id', 'route', $modulePrefix];
            }
            $shortcutButton = $buttonBar->makeShortcutButton()
                ->setModuleName($moduleName)
                ->setDisplayName($this->getLanguageService()->sL('LLL:EXT:apc_manager/Resources/Private/Language/locallang_be.xlf:module.shortcut_name'))
                ->setGetVariables($getVars);
            $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
    }

    /**
     * Returns the Language Service
     *
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Returns the current BE user.
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

}
