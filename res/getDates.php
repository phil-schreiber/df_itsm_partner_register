<?php

require_once(PATH_tslib.'class.tslib_pibase.php');





// Initialize FE user object:
class getDates extends tslib_pibase {
 public $cObj;
    public function main() {
            $this->cObj = t3lib_div::makeInstance('tslib_cObj');
        $feUserObject = tslib_eidtools::initFeUser();
        tslib_eidtools::connectDB(); 
        $seminar=$_POST['seminar'];
        
        if($seminar){
        $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
       
        $query=$GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
                '*,tx_seminars_seminars.uid as sid',
                'tx_seminars_seminars',
                'tx_seminars_seminars_place_mm',
                'tx_seminars_sites',
                'AND tx_seminars_seminars.begin_date>'.time().' AND tx_seminars_seminars.deleted =0 AND tx_seminars_seminars.object_type=2 AND tx_seminars_seminars.topic='.intval($seminar).''
                
                
                );
        
        while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
            $seminars[]=$rows;
            
        }
        

       
       $this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';	       
       $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);
       
       $template['total']=$this->cObj->getSubpart($this->templateFile,'###DATES-TEMPLATE###');
       $template['seminars']=$this->cObj->getSubpart($template['total'],"###DATE-ITEM###");
       
       foreach($seminars as $key => $value){
            
                    $categoryArray=array(
                        '###DATE_ID###'=>$value["sid"],
                        '###DATE_TITLE###'=>'von:'.date('d.m.Y',$value['begin_date']).' bis: '.date('d.m.Y',$value['end_date']).' in: '.$value['city'].' - '.$value['address']
                    );
                    $seminarItems.=$this->cObj->substituteMarkerArrayCached($template['seminars'],$categoryArray,array(),array());
                            
                }
        $subpartArray['###DATE-ITEM###']=$seminarItems;
        $content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);
        }else{
            $content=false;
        }
             

     return $content; 
    }
}

$output = t3lib_div::makeInstance('getDates');
echo $output->main();
?>
