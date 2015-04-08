<?php

require_once(PATH_tslib.'class.tslib_pibase.php');





// Initialize FE user object:
class getPrice extends tslib_pibase {
 public $cObj;
    public function main() {
            $this->cObj = t3lib_div::makeInstance('tslib_cObj');
        $feUserObject = tslib_eidtools::initFeUser();
        tslib_eidtools::connectDB();
        $id=$_POST['date'];
        $amount=$_POST['amount'];

        if($id){
        $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();

        $query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*, b.price_regular AS baseprice',
                'tx_seminars_seminars AS a JOIN tx_seminars_seminars AS b ON a.topic=b.uid',
                'a.uid='.intval($id).''

                );

        while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
            $seminars[]=$rows;
            $baseprice=$rows['baseprice'];
        }
       $baseComplete=intval($amount)*$baseprice;
        $userid=$GLOBALS['TSFE']->fe_user->user['uid'];
       $queryDiscount=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
               '*',
               'tx_dfitsmpartnerregister_partnerdiscount LEFT JOIN tx_dfitsmpartnerregister_partnerdiscount_seminars_mm on tx_dfitsmpartnerregister_partnerdiscount_seminars_mm.uid_local=tx_dfitsmpartnerregister_partnerdiscount.uid',
               'userid='.$userid.' AND tx_dfitsmpartnerregister_partnerdiscount.deleted=0 AND tx_dfitsmpartnerregister_partnerdiscount.maxamount<'.intval($amount).' AND tx_dfitsmpartnerregister_partnerdiscount_seminars_mm.uid_foreign IN('.$seminars[0]['uid'].','.$seminars[0]['topic'].') ORDER BY discount DESC'
               );
    while ($discountRows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($queryDiscount)){
        $discounts[]=$discountRows;
    }

       $this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';
       $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);

       $template['total']=$this->cObj->getSubpart($this->templateFile,'###PRICE-TEMPLATE###');

       $markerArrayMain=array(
         '###BASEPRICE###' => number_format($baseprice,2,',','.'),
           '###BASECOMPLETE###'=> number_format($baseComplete,2,',','.'),
           '###RABATT###'=>$discounts[0]['discount'],
           '###PRICE###'=> number_format(round($baseprice-($discounts[0]['discount']*$baseprice/100),2), 2,',','.'),
           '###COMPLETE###'=> number_format(round(($baseprice*intval($amount))-($discounts[0]['discount']*($baseprice*intval($amount))/100),2), 2,',','.'),
       );
        $content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);

      }

     return $content;
    }
}

$output = t3lib_div::makeInstance('getPrice');
echo $output->main();
?>
