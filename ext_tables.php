<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$TCA['tx_dfitsmpartnerregister_partnerregistration'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array(		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_dfitsmpartnerregister_partnerregistration.gif',
	),
);

$TCA['tx_dfitsmpartnerregister_partnerdiscount'] = array(
    'ctrl' => array(
        'title'     => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerdiscount',        
        'label'     => 'title',    
        'tstamp'    => 'tstamp',
        'crdate'    => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY crdate',    
        'delete' => 'deleted',    
        'enablecolumns' => array(        
            'disabled' => 'hidden',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_dfitsmpartnerregister_partnerdiscount.png',
    ),
);

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


/*if (TYPO3_MODE === 'BE') {
	t3lib_extMgm::addModulePath('web_txdfitsmpartnerregisterM1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
		
	t3lib_extMgm::addModule('web', 'txdfitsmpartnerregisterM1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}*/
// you add pi_flexform to be renderd when your plugin is shown
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
// now, add your flexform xml-file
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds_pi1.xml');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/partner_register_static_ts/', 'partner register static ts');
?>