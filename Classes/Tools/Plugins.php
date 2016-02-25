<?php
namespace SUDHAUS7\Sudhaus7Base\Tools;

class Plugins {
    public static function AddList($ext,$ns,$index) {
        $base = strtolower(str_replace('_','',$ext));
        $key = 'tx_'.$base.'_pi'.$index;
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup', '
### ADDING PLUGIN '.$ext.' '.$base.'
plugin.'.$key.' = USER
plugin.'.$key.' {
    userFunc = '.$ns.'\\Pi'.$index.'->main
}');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($key, 'setup','
### ADDING PLUGIN '.$ext.'
tt_content.list.20.'.$ext.'_pi'.$index.' = < plugin.'.$key.'
','defaultContentRendering');
    }
    public static function AddCtype() {

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
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPIFlexFormValue($ext . '_' . $id, 'FILE:EXT:'.$ext.'/Configuration/Flexforms/' . ucfirst($id) . '.xml');
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