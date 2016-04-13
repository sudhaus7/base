<?php
namespace SUDHAUS7\Sudhaus7Base\Tools;

class Plugins {

    public static function AddList($ext, $ns, $index)
    {
        $base = strtolower(str_replace('_', '', $ext));
        $key = 'tx_' . $base . '_pi' . $index;
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup', '
### ADDING PLUGIN ' . $ext . ' ' . $base . ' pi' . $index . '
plugin.' . $key . ' = USER
plugin.' . $key . ' {
    userFunc = ' . $ns . '\\Pi' . $index . '->main
}');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup', '
### ADDING PLUGIN ' . $ext . ' pi' . $index . '
tt_content.list.20.' . $ext . '_pi' . $index . ' = < plugin.' . $key . '
', 'defaultContentRendering');
    }

    public static function AddCtype($ext, $ns, $index)
    {
        $base = strtolower(str_replace('_', '', $ext));
        $key = $base . '_pi' . $index;
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup', '
### ADDING PLUGIN ' . $ext . ' ' . $base . ' pi' . $index . '
plugin.tx_' . $key . ' = USER
plugin.tx_' . $key . ' {
    userFunc = ' . $ns . '\\Pi' . $index . '->main
}
tt_content.' . $key . ' = COA
tt_content.' . $key . ' {
    20 =< plugin.tx_' . $key . '
}');
    }

    public static function AddCtypeTtcontent($ext, $i, $flex = false, $config = array())
    {
        $frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';
        $languageFilePrefix = 'LLL:EXT:' . $ext . '/Resources/Private/Language/locallang.xlf:';
        $id = 'pi' . $i;
        $extensionName = strtolower(str_replace('_', '', $ext));
        $pluginSignature = $extensionName . '_pi' . $i;

        $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['default'] = $pluginSignature;
        $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$pluginSignature] = 'mimetypes-x-content-text';

        $flexfield = $flex ? 'sudhaus7_flexform,' : '';
        $addFields = isset($config['additionalFields']) ? $config['additionalFields'] : '';
        $columnsOverrides = isset($config['columnsOverrides']) ? $config['columnsOverrides'] : [];

        $GLOBALS['TCA']['tt_content']['types'][$pluginSignature] = [
            'showitem' => '
				--palette--;' . $frontendLanguageFilePrefix . 'palette.general;general,
				--palette--;' . $frontendLanguageFilePrefix . 'palette.header;header,' . $flexfield . $addFields . '
			--div--;' . $frontendLanguageFilePrefix . 'tabs.appearance,
				layout;' . $frontendLanguageFilePrefix . 'layout_formlabel,tx_bfactorbkv4_colorscheme,tx_bfactorbkv4_screenwidth,
				--palette--;' . $frontendLanguageFilePrefix . 'palette.appearanceLinks;appearanceLinks,
			--div--;' . $frontendLanguageFilePrefix . 'tabs.access,
				hidden;' . $frontendLanguageFilePrefix . 'field.default.hidden,
				--palette--;' . $frontendLanguageFilePrefix . 'palette.access;access,
			--div--;' . $frontendLanguageFilePrefix . 'tabs.extended
		',
            'columnsOverrides' => $columnsOverrides
        ];
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                $languageFilePrefix . 'tt_content.' . $pluginSignature,
                $pluginSignature,
                'content-text'
            ]
        );

        if ($flex) {
            \SUDHAUS7\Sudhaus7Base\Tools\Plugins::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $ext . '/Configuration/Flexforms/' . ucfirst($id) . '.xml');
        }


    }

    /**
     * Adds an entry to the "ds" array of the tt_content field "sudhaus7_flexform".
     * This is used by plugins to add a flexform XML reference / content for use when they are selected as plugin or content element.
     *
     * @param string $piKeyToMatch Plugin key as used in the list_type field. Use the asterisk * to match all list_type values.
     * @param string $value Either a reference to a flex-form XML file (eg. "FILE:EXT:newloginbox/flexform_ds.xml") or the XML directly.
     * @param string $CTypeToMatch Value of tt_content.CType (Content Type) to match. The default is "list" which corresponds to the "Insert Plugin" content element.  Use the asterisk * to match all CType values.
     * @return void
     * @see addPlugin()
     */
    public static function addPiFlexFormValue($piKeyToMatch, $value, $CTypeToMatch = 'list')
    {
        if (is_array($GLOBALS['TCA']['tt_content']['columns']) && is_array($GLOBALS['TCA']['tt_content']['columns']['sudhaus7_flexform']['config']['ds'])) {
            $GLOBALS['TCA']['tt_content']['columns']['sudhaus7_flexform']['config']['ds'][$piKeyToMatch] = $value;
        }
    }
    
    public static function AddListTtcontent($ext,$i,$flex=false,$wizard=false,$config = array()) {

        global $TCA;
        $id = 'pi' . $i;

        if (empty($config)) {
            $config['subtypes_excludelist'] = 'layout,select_key';

        }


        if ($flex) {
            if (!isset($config['subtypes_addlist']) || empty($config['subtypes_addlist'])) {
                $config['subtypes_addlist'] = 'pi_flexform';
            } else {
                if (strpos($config['subtypes_addlist'],'pi_flexform') === false) {
                    $config['subtypes_addlist'].=',pi_flexform';
                }
            }
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($ext . '_' . $id, 'FILE:EXT:'.$ext.'/Configuration/Flexforms/' . ucfirst($id) . '.xml');
        }
        foreach ($config as $k=>$v) {
            $TCA['tt_content']['types']['list'][$k][$ext . '_' . $id] = $v;
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(Array(
            'LLL:EXT:'.$ext.'/Resources/Private/Language/locallang.xlf:tt_content.' . $ext . '_' . $id,
            $ext . '_' . $id,
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($ext) . 'ext_icon.gif'
        ), 'list_type');
    
        if ($wizard) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
            mod.wizards.newContentElement.wizardItems {
                plugins {
                    elements {
                        ' . $ext . '_' . $id . ' {
                            title = LLL:EXT:' . $ext . '/Resources/Private/Language/locallang.xlf:tt_content.' . $ext . '_' . $id . '
                            description = LLL:EXT:' . $ext . '/Resources/Private/Language/locallang.xlf:tt_content.' . $ext . '_' . $id . '.description
                            icon = EXT:' . $ext . '/Resources/Public/Icons/' . ucfirst($id) . '.png
                            tt_content_defValues {
                                CType = list
                                list_type = ' . $ext . '_' . $id . '
                            }
                        }
                    }
                    show := addToList(' . $ext . '_' . $id . ')
                }
            }
            ');
            //  #show := addToList(' . $ext . '_' . $id . ')
        }
    }
    
    
}
