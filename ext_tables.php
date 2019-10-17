<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {

        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'ThomasK.TkCache',
                'tools',
                'tk_cache',
                'bottom',
                [
                    'BackendCache' => 'index, apcu, opc, redis, config'
                ],
                [
                    'access' => 'admin',
                    'icon' => 'EXT:tk_cache/Resources/Public/Icons/Extension.svg',
                    'labels' => 'LLL:EXT:tk_cache/Resources/Private/Language/locallang_mod.xlf',
                ]
            );
        }
    }
);
