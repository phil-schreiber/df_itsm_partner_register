<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_dfitsmpartnerregister_partnerregistration'] = array(
	'ctrl' => $TCA['tx_dfitsmpartnerregister_partnerregistration']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,bookingdate,seminarid,userid,usergroup,amount,discount,price,lastname,firstname,phone,email,message'
	),
	'feInterface' => $TCA['tx_dfitsmpartnerregister_partnerregistration']['feInterface'],
	'columns' => array(
		'hidden' => array(		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'bookingdate' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.bookingdate',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'seminarid' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.seminarid',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'userid' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.userid',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'usergroup' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.usergroup',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'amount' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.amount',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
            'discount' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.discount',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
            'price' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.price',		
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0
			)
		),
            'lastname' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:df_iqtest/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.lastname',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'trim',
			)
		),
            'firstname' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:df_iqtest/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.firstname',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'trim',
			)
		),
            'phone' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:df_iqtest/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.phone',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'trim',
			)
		),
            'email' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:df_iqtest/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.email',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'trim',
			)
		),
            'message' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:df_iqtest/locallang_db.xml:tx_dfitsmpartnerregister_partnerregistration.message',
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, bookingdate, seminarid, userid, usergroup, amount, discount, price, lastname, firstname, phone, email, message')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_dfitsmpartnerregister_partnerdiscount'] = array(
    'ctrl' => $TCA['tx_dfitsmpartnerregister_partnerdiscount']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,title,userid,usergroup,discount,maxamount,seminars'
    ),
    'feInterface' => $TCA['tx_dfitsmpartnerregister_partnerdiscount']['feInterface'],
    'columns' => array(
        'hidden' => array(        
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config'  => array(
                'type'    => 'check',
                'default' => '0'
            )
        ),
        'title' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerdiscount.title',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'trim',
			)
		),
        
        'userid' => array(        
            'exclude' => 0,        
            'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerdiscount.userid',        
            'config' => array(
                'type' => 'group',    
                'internal_type' => 'db',    
                'allowed' => 'fe_users',    
                'size' => 1,    
                'minitems' => 1,
                'maxitems' => 1,
            )
        ),
        
        'discount' => array(        
            'exclude' => 0,        
            'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerdiscount.discount',        
            'config' => array(
                'type'     => 'input',
                'size'     => '4',
                'max'      => '4',
                'eval'     => 'int',
                'checkbox' => '0',
                'range'    => array(
                    'upper' => '1000',
                    'lower' => '0'
                ),
                'default' => 0
            )
        ),
        'maxamount' => array(        
            'exclude' => 0,        
            'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerdiscount.maxamount',        
            'config' => array(
                'type'     => 'input',
                'size'     => '4',
                'max'      => '4',
                'eval'     => 'int',
                'checkbox' => '0',
                'range'    => array(
                    'upper' => '1000',
                    'lower' => '0'
                ),
                'default' => 0
            )
        ),
        'seminars' => array(        
            'exclude' => 0,        
            'label' => 'LLL:EXT:df_itsm_partner_register/locallang_db.xml:tx_dfitsmpartnerregister_partnerdiscount.seminars',        
            'config' => array(
                'type' => 'group',    
                'internal_type' => 'db',    
                'allowed' => 'tx_seminars_seminars',    
                'size' => 10,    
                'minitems' => 0,
                'maxitems' => 20,    
                "MM" => "tx_dfitsmpartnerregister_partnerdiscount_seminars_mm",
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'hidden;;1;;1-1-1, title, userid, usergroup, discount, maxamount, seminars')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);
?>