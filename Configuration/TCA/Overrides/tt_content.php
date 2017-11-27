<?php
call_user_func(function () {
    $fields = [
        "sudhaus7_flexform" => [
            "exclude" => 0,
            "label" => "Konfigurationen",
            "config" => [
                "type" => "flex",
                "ds_pointerField" => "CType",
                "ds" => [
	                "default" => '
                        <T3DataStructure>
                          <ROOT>
                            <type>array</type>
                            <el>
                                <!-- Repeat an element like "xmlTitle" beneath for as many elements you like. Remember to name them uniquely  -->
                              <xmlTitle>
                                <TCEforms>
                                    <label>The Title:</label>
                                    <config>
                                        <type>input</type>
                                        <size>48</size>
                                    </config>
                                </TCEforms>
                              </xmlTitle>
                            </el>
                          </ROOT>
                        </T3DataStructure>
                    ',
                ],
                /*
                    $_EXTKEY.'_pi1'=>'FILE:EXT:bfactor_elements/pi1/flexform.xml',
                    $_EXTKEY.'_pi2'=>'FILE:EXT:bfactor_elements/pi2/flexform.xml',
                    $_EXTKEY.'_pi4'=>'FILE:EXT:bfactor_elements/pi4/flexform.xml',
                    $_EXTKEY.'_pi5'=>'FILE:EXT:bfactor_elements/pi5/flexform.xml',
                    $_EXTKEY.'_pi7'=>'FILE:EXT:bfactor_elements/pi7/flexform.xml',
                    $_EXTKEY.'_pi10'=>'FILE:EXT:bfactor_elements/pi10/flexform.xml',
                */
            ]
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $fields
    );
});
