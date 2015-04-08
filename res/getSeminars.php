<?php

require_once(PATH_tslib.'class.tslib_pibase.php');





// Initialize FE user object:
class getSeminars extends tslib_pibase {
 public $cObj;
    public function main() {
            $this->cObj = t3lib_div::makeInstance('tslib_cObj');
        $feUserObject = tslib_eidtools::initFeUser();
        tslib_eidtools::connectDB(); 
        $category=$_POST['categories'];
        if($category){
        $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
        
        $query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_seminars_seminars',                
                'deleted =0 AND object_type=1 AND event_type='.intval($category).''
                
                
                );
        while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
            $seminars[]=$rows;
        }
       
       $this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';	       
       $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);
       
       $template['total']=$this->cObj->getSubpart($this->templateFile,'###SEMINARY-TEMPLATE###');
       $template['seminars']=$this->cObj->getSubpart($template['total'],"###SEMINAR-ITEM###");
       
       foreach($seminars as $key => $value){
            
                    $categoryArray=array(
                        '###SEMINAR_ID###'=>$value["uid"],
                        '###SEMINAR_TITLE###'=>$value['title']
                    );
                    $seminarItems.=$this->cObj->substituteMarkerArrayCached($template['seminars'],$categoryArray,array(),array());
                            
                }
        $subpartArray['###SEMINAR-ITEM###']=$seminarItems;
        $content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);
        }else{
            $content=false;
        }
             

     return $content; 
    }
}

$output = t3lib_div::makeInstance('getSeminars');
echo $output->main();
?>
