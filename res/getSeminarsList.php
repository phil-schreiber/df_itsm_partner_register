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
                'tx_seminars_seminars.uid AS sid, tx_seminars_seminars.title AS stitle,tx_seminars_seminars.begin_date, tx_seminars_seminars.end_date, tx_seminars_seminars.description, tx_seminars_categories.uid AS cid, tx_seminars_categories.title AS ctitle, descriptor.teaser AS descriptorteaser,descriptor.title AS dtitle, descriptor.uid AS duid,tx_seminars_sites.title AS scity, tx_seminars_sites.uid AS cityid, b.price_regular AS price',
                'tx_seminars_seminars JOIN tx_seminars_seminars AS b ON tx_seminars_seminars.topic=b.uid LEFT JOIN tx_seminars_event_types ON tx_seminars_event_types.uid=tx_seminars_seminars.event_type LEFT JOIN tx_seminars_seminars AS descriptor ON tx_seminars_seminars.topic=descriptor.uid AND descriptor.topic=0  LEFT JOIN tx_seminars_seminars_place_mm ON tx_seminars_seminars_place_mm.uid_local=tx_seminars_seminars.uid LEFT JOIN tx_seminars_sites ON tx_seminars_sites.uid = tx_seminars_seminars_place_mm.uid_foreign LEFT JOIN tx_seminars_seminars_categories_mm ON tx_seminars_seminars_categories_mm.uid_local =tx_seminars_seminars.uid LEFT JOIN tx_seminars_categories ON tx_seminars_categories.uid=tx_seminars_seminars_categories_mm.uid_foreign',
                'tx_seminars_seminars.deleted =0 AND tx_seminars_seminars.object_type=2 AND descriptor.event_type='.intval($category).''


                );
        while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
            /*descriptorteaser*/
            $seminars[$rows['sid']]=$rows;
            $city[$rows['cityid']]=$rows['scity'];
            $categories[$rows['duid']]=$rows['dtitle'];
        }

        	$userid=$GLOBALS['TSFE']->fe_user->user['uid'];
        	$queryDiscount=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
        	       '*',
        	       'tx_dfitsmpartnerregister_partnerdiscount',
        	       'userid='.$userid.' AND tx_dfitsmpartnerregister_partnerdiscount.deleted=0 ORDER BY discount DESC LIMIT 1'
        	       );
        	while ($discountRows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($queryDiscount)){
        		$personalDiscount = $discountRows['discount'];
        	}

       $this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';
       $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);

       $template['total']=$this->cObj->getSubpart($this->templateFile,'###SEMINARS-TEMPLATE###');
       $template['seminars']=$this->cObj->getSubpart($template['total'],"###SEMINAR-ITEM###");
       $template['category']=$this->cObj->getSubpart($template['total'],"###CATEGORY-OPTIONS###");
       $template['city']=$this->cObj->getSubpart($template['total'],"###CITIES###");

       foreach($seminars as $key => $value){

                    $categoryArray=array(
                        '###SEMINAR-ID###'=>$value["sid"],
                        '###SEMINAR-TITLE###'=>$value['stitle'],
                        '###SEMINAR-PRICE###'=> number_format(($value['price'] * ((100 - $personalDiscount) / 100)),2,',','.'),                      			 '###DESCRIPTION###'=>$value['descriptorteaser'],
                        '###SEMINAR-DATE###'=>date('d.m.',$value['begin_date']).' - '.date('d.m.Y',$value['end_date']),
                        '###CITY-TITLE###'=>$value['scity'],
                        '###SPEC-DATE###'=>date('Y',$value['begin_date']).date('m',$value['begin_date']),
                        '###YEAR###'=>date('Y',$value['begin_date']),
                        '###CAT-NO-SPACES###'=>preg_replace(array('/ /','/®/'),array('_',''),$value['dtitle'])


                    );
             $seminarItems.=$this->cObj->substituteMarkerArrayCached($template['seminars'],$categoryArray,array(),array());

         }

        foreach($city as $key => $value){
            $cityArray=array(
              '###CITY###' =>$value
            );
            $cityItems.=$this->cObj->substituteMarkerArrayCached($template['city'],$cityArray,array(),array());
        }

        foreach($categories as $key => $value){
            $catArray=array(
              '###CATEGORY###'  =>$value,
              '###CATEGORY-NOSPACES###'=>preg_replace(array('/ /','/®/'),array('_',''),$value)
            );
            $categoryItems.=$this->cObj->substituteMarkerArrayCached($template['category'],$catArray,array(),array());
        }
        $subpartArray['###SEMINAR-ITEM###']=$seminarItems;
        $subpartArray['###CATEGORY-OPTIONS###']=$categoryItems;
        $subpartArray['###CITIES###']=$cityItems;
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
