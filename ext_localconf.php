<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$TYPO3_CONF_VARS['FE']['eID_include']['getSeminars'] = 'EXT:df_itsm_partner_register/res/getSeminars.php';
$TYPO3_CONF_VARS['FE']['eID_include']['getDates'] = 'EXT:df_itsm_partner_register/res/getDates.php';
$TYPO3_CONF_VARS['FE']['eID_include']['getVacancies'] = 'EXT:df_itsm_partner_register/res/getVacancies.php';
$TYPO3_CONF_VARS['FE']['eID_include']['getPrice'] = 'EXT:df_itsm_partner_register/res/getPrice.php';
$TYPO3_CONF_VARS['FE']['eID_include']['order'] = 'EXT:df_itsm_partner_register/res/order.php';
$TYPO3_CONF_VARS['FE']['eID_include']['getSeminarsList'] = 'EXT:df_itsm_partner_register/res/getSeminarsList.php';


t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_dfitsmpartnerregister_pi1.php', '_pi1', 'list_type', 0);
?>