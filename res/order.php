<?php

require_once(PATH_tslib.'class.tslib_pibase.php');





// Initialize FE user object:
class getPrice extends tslib_pibase {
 public $cObj;
    public function main() {
        $this->cObj = t3lib_div::makeInstance('tslib_cObj');
        $feUserObject = tslib_eidtools::initFeUser();
        tslib_eidtools::connectDB();


        $amount=intval($_POST['amount']);
        $lastname=  mysql_real_escape_string($_POST['last_name']);
        $firstname=  mysql_real_escape_string($_POST['first_name']);
        $email=  mysql_real_escape_string($_POST['email']);
        $phone=  mysql_real_escape_string($_POST['phone']);
        $message=  mysql_real_escape_string($_POST['message']);
        $category=  intval($_POST['categories']);
        $seminar=  intval($_POST['seminar']);
        $id=intval($_POST['date']);


        if($id){
        $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->getCompressedTCarray();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();

        $query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*, b.price_regular AS baseprice, a.begin_date AS sstart, a.end_date as ssend',
                'tx_seminars_seminars AS a JOIN tx_seminars_seminars AS b ON a.topic=b.uid',
                'a.uid='.$id.''

                );

        while($rows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
            $seminars[]=$rows;
            $baseprice=$rows['baseprice'];
        }
       $baseComplete=$amount*$baseprice;
        $userid=$GLOBALS['TSFE']->fe_user->user['uid'];
       $queryDiscount=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
               '*',
               'tx_dfitsmpartnerregister_partnerdiscount LEFT JOIN tx_dfitsmpartnerregister_partnerdiscount_seminars_mm ON tx_dfitsmpartnerregister_partnerdiscount_seminars_mm.uid_local=tx_dfitsmpartnerregister_partnerdiscount.uid',
               'userid='.$userid.' AND tx_dfitsmpartnerregister_partnerdiscount.deleted=0 AND tx_dfitsmpartnerregister_partnerdiscount.maxamount<'.$amount.' AND tx_dfitsmpartnerregister_partnerdiscount_seminars_mm.uid_foreign IN('.$seminars[0]['uid'].','.$seminars[0]['topic'].') ORDER BY discount DESC'
               );
    while ($discountRows=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($queryDiscount)){
        $discounts[]=$discountRows;
    }
        $pid=0;
        if($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_dfitsmpartnerregister_pi1.']['seminarsStorageFolder']){
            $pid=$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_dfitsmpartnerregister_pi1.']['seminarsStorageFolder'];
        }
        $dicountBaseprice=round($baseprice-($discounts[0]['discount']*$baseprice/100),2);

       	for($i=0;$i<30;$i++){

       		if($_POST['firstname_'.$i]){

       			if($_POST['testcost_'. $i] == 'on'){

					$testCostCounter++;
       			}
       		}
       	}

    	$testCosts = $testCostCounter * floatval($_POST['testCosts']);



        $discountCompletePrice=number_format((round(($baseprice*$amount)-($discounts[0]['discount']*($baseprice*$amount)/100),2) + $testCosts),2,',','.');


        $insertArray=array(
            'crdate'=>time(),
            'tstamp'=>time(),
            'bookingdate'=>time(),
            'seminarid'=>$id,
           'userid' =>$userid,
            'usergroup'=>$GLOBALS['TSFE']->fe_user->user['usergroup'],
            'amount'=>$amount,
            'firstname'=>$firstname,
            'lastname'=>$lastname,
           'email' =>$email,
            'phone'=>$phone,
            'message'=>$message,
            'discount'=>$discounts[0]['discount'],
            'price'=>$discountCompletePrice




        );


        	for($i=0;$i<30;$i++){

        		if($_POST['firstname_'.$i]){
        			$firstName = $_POST['firstname_'.$i];
        			$lastName = $_POST['lastname_'.$i];

        			if($_POST['testcost_'. $i]){
        				$testCosts = 'inkl. Prüfung';
        			}else{
        				$testCosts = '';
        			}

        			$attendees[] = $firstName .' '. $lastName .' | '. $testCosts;



        		}


        	}

        	$attendeeString = implode('\n',$attendees);

        $seminarsInsertArray=array(
            'pid'=>$pid,
            'tstamp'=>time(),
            'crdate'=>time(),
            'title'=>'"'.$firstname.' '.$lastname.' / '.$seminars[0]['title'].' / '.date('d.m.Y',$seminars[0]['sstart']).'-'.date('d.m.Y',$seminars[0]['ssend']).'"',
            'user' =>$userid,
            'seminar'=>$id,
            'price'=>$dicountBaseprice,
            'seats'=>$amount,
            'total_price'=>$discountCompletePrice,
            'notes'=>'"'.$message.'"',
            'attendees_names' => '"'. $attendeeString .'"'



        );



        $insertQuery=$GLOBALS['TYPO3_DB']->exec_INSERTquery(
                'tx_dfitsmpartnerregister_partnerregistration',
                $insertArray
                );


        	$insertQuerySeminars =$GLOBALS['TYPO3_DB']->exec_INSERTquery(
        	        'tx_seminars_attendances',
        	        $seminarsInsertArray,
        	        'attendees_names'
        	        );

        $this->conf['templateFile'] = 'typo3conf/ext/df_itsm_partner_register/res/template.html';
       $this->templateFile=$this->cObj->fileResource($this->conf['templateFile']);

       $template['total']=$this->cObj->getSubpart($this->templateFile,'###MAIL###');
       $template['plain']=$this->cObj->getSubpart($this->templateFile,'###MAIL-PLAIN###');
       $markerArrayMain=array(
         '###LAST_NAME###'  =>$insertArray['lastname'],
           '###SEMINAR-DATA###'=>$seminarsInsertArray['title'],
           '###AMOUNT###'=>$amount,
           '###PRICE###'=>$discountCompletePrice
       );
        $content['HTML'] = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);
        $content['PLAIN']=$this->cObj->substituteMarkerArrayCached($template['plain'],$markerArrayMain,$subpartArray,$wrappedSubpartArray);

       $this->sendMails($insertArray,$content);
      }

     return TRUE;
    }

    function sendMails($insertArray,$bodyText){
         $betreffText="Vielen Dank für Ihre Reservierung";
		$betreff    = '=?UTF-8?B?'.base64_encode($betreffText).'?=';
		$antwortan  = 'info@itsm-consulting.de';
                $absender=$antwortan;
		$bcc=$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_dfitsmpartnerregister_pi1.']['ccAdminNotify'];
                $empfaenger=$insertArray['email'];
		$header .= "From: ".$absender."\n";
		$header .= "Reply-To: ".$antwortan."\n";
                if($bcc){
		$header .= "Bcc: ".$bcc."\n";
                }
		$header .= "X-Mailer: PHP ".phpversion()."\n";
		$header .= "MIME-Version: 1.0\n";

		$header .= "Content-Type: multipart/alternative;\n boundary=\"----------multipart_alternative_boundary\"\n";


		$content .= "------------multipart_alternative_boundary\n";
		$content .= "Content-Type: text/plain; charset=utf-8\n";
		$content .= "Content-Transfer-Encoding: quoted-printable\n";
                $content .=$bodyText['PLAIN'];
                $content .= "\r\n\r\n------------multipart_alternative_boundary\r\n";
		$content .= "Content-Type: text/html; charset=utf-8\r\n";
		$content .= "Content-Transfer-Encoding: 8bit\r\n";
		$content .=$bodyText['HTML'];

		mail(
		$empfaenger,
			$betreff,
			$content,
			$header
			);
    }
}

$output = t3lib_div::makeInstance('getPrice');
echo $output->main();
?>
