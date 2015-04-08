<?php

require_once(PATH_tslib.'class.tslib_pibase.php');





// Initialize FE user object:
class getVacancies extends tslib_pibase {
 public $cObj;
    public function main() {
            $this->cObj = t3lib_div::makeInstance('tslib_cObj');
        $feUserObject = tslib_eidtools::initFeUser();
        tslib_eidtools::connectDB();
        $date=$_POST['date'];

        if($date){
        $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();

        $query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*, (SELECT SUM(seats) FROM tx_seminars_attendances WHERE tx_seminars_attendances.deleted=0 AND tx_seminars_attendances.seminar='.intval($date).') as attendances, tx_seminars_checkboxes.title AS testCosts',
                'tx_seminars_seminars LEFT JOIN tx_seminars_seminars_checkboxes_mm ON (tx_seminars_seminars_checkboxes_mm.uid_local = tx_seminars_seminars.topic) LEFT JOIN tx_seminars_checkboxes ON (tx_seminars_checkboxes.uid = tx_seminars_seminars_checkboxes_mm.uid_foreign)',
                'tx_seminars_seminars.deleted =0 AND tx_seminars_seminars.uid='.intval($date).''

                );

        while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
            $seminars[]=$rows;

        }

        	$explodeString = explode(' ',$seminars[0]['testCosts']);
        	$seminarCosts = '';
        	print_r($explodeString);

        	foreach($explodeString AS $key => $value){

        		$testString = str_replace(',','.',str_replace('€','',$value));

        		if(floatval($testString) > 1){
        			echo floatval($testString);
					$seminarCosts = $testString;
        		}

        	}





       $remaining= $seminars[0]['attendees_max'] - $seminars[0]['attendances'];

       $this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';
       $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);

       $template['total']=$this->cObj->getSubpart($this->templateFile,'###AMOUNT-TEMPLATE###');
       $template['seminars']=$this->cObj->getSubpart($template['total'],"###AMOUNT-ITEM###");
       if($remaining > 0){
       for($i=1; $i<=$remaining;$i++){

                    $categoryArray=array(
                        '###AMOUNT###'=>$i,
                        '###SEMINAR_COSTS###' => $seminarCosts .'|'. $seminars[0]['testCosts']

                    );

                    $seminarItems.=$this->cObj->substituteMarkerArrayCached($template['seminars'],$categoryArray,array(),array());

                }
        }else{
            $categoryArray=array(
                        '###AMOUNT###'=>"leider ausgebucht"

                    );
                    $i++;
                    $seminarItems.=$this->cObj->substituteMarkerArrayCached($template['seminars'],$categoryArray,array(),array());

        }
        $subpartArray['###AMOUNT-ITEM###']=$seminarItems;

        $content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);
        }else{
            $content=false;
        }


     return $content;
    }
}

$output = t3lib_div::makeInstance('getVacancies');
echo $output->main();
?>
