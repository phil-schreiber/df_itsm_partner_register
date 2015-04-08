#
# Table structure for table 'tx_dfitsmpartnerregister_partnerregistration'
#
CREATE TABLE tx_dfitsmpartnerregister_partnerregistration (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	bookingdate int(11) DEFAULT '0' NOT NULL,
	seminarid int(11) DEFAULT '0' NOT NULL,
	userid int(11) DEFAULT '0' NOT NULL,
	usergroup int(11) DEFAULT '0' NOT NULL,
	amount int(11) DEFAULT '0' NOT NULL,
	firstname varchar(255) DEFAULT '' NOT NULL,
	lastname varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	message text,
        discount decimal(10,3) DEFAULT '0.000' NOT NULL,
        price decimal(10,3) DEFAULT '0.000' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;

#
# Table structure for table 'tx_dfitsmpartnerregister_partnerdiscount_seminars_mm'
# 
#
CREATE TABLE tx_dfitsmpartnerregister_partnerdiscount_seminars_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_dfitsmpartnerregister_partnerdiscount'
#
CREATE TABLE tx_dfitsmpartnerregister_partnerdiscount (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    title varchar(30) DEFAULT '' NOT NULL,
    userid text,
    discount int(11) DEFAULT '0' NOT NULL,
    maxamount int(11) DEFAULT '0' NOT NULL,
    seminars int(11) DEFAULT '0' NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
) ENGINE=InnoDB;