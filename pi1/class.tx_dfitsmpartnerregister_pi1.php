<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Philipp Schreiber <schreiber@denkfabrik-group.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

// require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * Plugin 'DF Partner Seminaranmeldung' for the 'df_itsm_partner_register' extension.
 *
 * @author	Philipp Schreiber <schreiber@denkfabrik-group.com>
 * @package	TYPO3
 * @subpackage	tx_dfitsmpartnerregister
 */
class tx_dfitsmpartnerregister_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_dfitsmpartnerregister_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_dfitsmpartnerregister_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'df_itsm_partner_register';	// The extension key.
	public $pi_checkCHash = TRUE;

	/**
	 * The main method of the Plugin.
	 *
	 * @param string $content The Plugin content
	 * @param array $conf The Plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($content, array $conf) {

                $this->init($conf);
                $mode=$this->pi_getFFvalue($this->cObj->data['pi_flexform'],'whattodisplay','sDEF');
                switch($mode){
                    case 0:
                        $content=$this->orderView();
                        break;
                    case 1:
                        $content=$this->overView();
                      break;
                  default:
                      $content=$this->orderView();
                }





		return $this->pi_wrapInBaseClass($content);
	}

        private function orderView(){
            $template['total']=$this->cObj->getSubpart($this->templateFile,'###FORM-TEMPLATE###');
                $this->path=$this->pi_getPageLink($GLOBALS['TSFE']->id);

        		$config['file'] = 'uploads/tx_srfeuserregister/'. $GLOBALS['TSFE']->fe_user->user['image'];
				$config['file.']['maxW'] = '180';
        		$config['params'] = 'style="float:right;margin-left:10px;"';


				$imageCode = $this->cObj->IMAGE($config);

                $markerArrayMain=array(
                    "###SALUTATION###"=>$GLOBALS['TSFE']->fe_user->user['gender'] == 0 ? 'Sehr geehrter Herr' : 'Sehr geehrte Frau',
                    '###IMAGECODE###' => $imageCode,
					"###USER_LASTNAME###"=>$GLOBALS['TSFE']->fe_user->user['last_name'],
                    "###USER_FIRSTNAME###"=>$GLOBALS['TSFE']->fe_user->user['first_name'],
                    "###USER_COMPANY###"=>$GLOBALS['TSFE']->fe_user->user['company'],
                    "###USER_ID###"=>$GLOBALS['TSFE']->fe_user->user['uid'],
                    "###USER_GROUP###"=>$GLOBALS['TSFE']->fe_user->user['usergroup'],
                    "###USER_EMAIL###"=>$GLOBALS['TSFE']->fe_user->user['email'],
                    "###USER_PHONE###"=>$GLOBALS['TSFE']->fe_user->user['telephone'],
                    '###PATH###'=>$this->path,
                    '###PATH-DATA###'=>$this->pi_getPageLink($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_dfitsmpartnerregister_pi1.']['userDataPageId'])

                );
                $categories=$this->getCategories();

                $template['categoryItems']=$this->cObj->getSubpart($template['total'],'###CATEGORIES###');
                foreach($categories as $key => $value){
                    $categoryArray=array(
                        '###CATEGORY_ID###'=>$value["uid"],
                        '###CATEGORY_TITLE###'=>$value['title']
                    );
                    $categoryItems.=$this->cObj->substituteMarkerArrayCached($template['categoryItems'],$categoryArray,array(),array());

                }
                $subpartArray['###CATEGORIES###']=$categoryItems;
		$content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);

                $template['startjscript'] = $this->cObj->getSubpart($this->templateFile,'###JSCRIPT###');
                $markerArray=array(
			'###PATH###'=>$this->path
			);
                $startjscript=$this->cObj->substituteMarkerArrayCached($template['startjscript'],$markerArray,$subpartArray,$wrappedSubpartArray);
                $css='<link rel="stylesheet" type="text/css" href="typo3conf/ext/df_itsm_partner_register/res/styles.css">
                     <link rel="stylesheet" type="text/css" href="typo3conf/ext/df_itsm_partner_register/res/isotope_styles.css" media="all">';
                $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->prefixId] = $css.$startjscript;
                return $content;
        }

        private function overView(){
            $userid=$GLOBALS['TSFE']->fe_user->user['uid'];
            $template['total']=$this->cObj->getSubpart($this->templateFile,'###OVERVIEW-TEMPLATE###');
            $template['items']=$this->cObj->getSubpart($template['total'],"###ORDER-ITEM### ");


            $query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*,tx_dfitsmpartnerregister_partnerregistration.price AS endpreis, t.title AS stitle, tx_seminars_seminars.begin_date AS begin_date_1, tx_seminars_seminars.end_date AS end_date_1',
                    'tx_dfitsmpartnerregister_partnerregistration LEFT JOIN tx_seminars_attendances AS s ON (s.seminar = tx_dfitsmpartnerregister_partnerregistration.seminarid AND s.crdate = tx_dfitsmpartnerregister_partnerregistration.bookingdate), tx_seminars_seminars LEFT JOIN tx_seminars_seminars AS t ON (t.uid = tx_seminars_seminars.topic),tx_seminars_seminars_place_mm,tx_seminars_sites',
                    'tx_dfitsmpartnerregister_partnerregistration.seminarid=tx_seminars_seminars.uid AND tx_seminars_seminars_place_mm.uid_local= tx_seminars_seminars.uid AND tx_seminars_seminars_place_mm.uid_foreign=tx_seminars_sites.uid AND tx_dfitsmpartnerregister_partnerregistration.deleted=0 AND tx_dfitsmpartnerregister_partnerregistration.userid='.intval($userid),
                    '',
                    'bookingdate DESC'
                    );

            while($queryRow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
				//t3lib_utility_Debug::debug($queryRow);
				$itemArray=array(
                    '###ORDER-DATE###'=>date('d.m.Y',$queryRow['bookingdate']),
                    '###SEMINAR###'=>$queryRow['stitle'],
                    '###SEMINAR-DATE###'=>date('d.m.Y',$queryRow['begin_date_1']).'<br>bis<br /> '.date('d.m.Y',$queryRow['end_date_1']),
                    '###ADDRESS###'=>$queryRow['title'],
                    '###AMOUNT###'=>$queryRow['amount'],
                    '###PRICE###'=> $queryRow['endpreis'] .' Euro (zzgl. MwSt) <br />bei '.number_format($queryRow['discount'],2,',','.') .'% Rabatt',
                    '###UID###' => $queryRow['bookingdate'],
                    '###DESCRIPTION###' => $queryRow['teaser'],
                    '###LOCATION###' => nl2br($queryRow['address']),
                    '###SEATS###' => $queryRow['seats'],
                    '###ATTENDEES###' => nl2br($queryRow['attendees_names'])
                );
                $orderItems.=$this->cObj->substituteMarkerArrayCached($template['items'],$itemArray,array(),array());
            }
            $subpartArray['###ORDER-ITEM###']=$orderItems;
            $content=$this->cObj->substituteMarkerArrayCached($template['total'],array(),$subpartArray,array());

            $css='<link rel="stylesheet" type="text/css" href="typo3conf/ext/df_itsm_partner_register/res/styles.css">';
               $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->prefixId] = $css;
            return $content;

        }

        private function getCategories(){
            $query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    '*',
                    'tx_seminars_event_types',
                    "deleted=0"
                    );
            while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
                $returnArray[]=$rows;
            }
            return $returnArray;
        }
        private function init(){
            $this->conf = $conf;
            $this->pi_setPiVarDefaults();
            $this->pi_loadLL();
            $this->pi_USER_INT_obj = 1;
            $this->pi_initPIflexForm();
            if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'templateFile', 'sDEF')) {
			$this->conf['templateFile'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'templateFile', 'sDEF');
		} else if($this->conf['templateFile'] && !$this->pi_getFFvalue($this->cObj->data['pi_flexform'],'templateFile', 'sDEF')){
			$this->conf['templateFile']=$this->conf['templateFile'];
		} else {
			$this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';
		}
             $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);

        }
}



if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/df_itsm_partner_register/pi1/class.tx_dfitsmpartnerregister_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/df_itsm_partner_register/pi1/class.tx_dfitsmpartnerregister_pi1.php']);
}

?>