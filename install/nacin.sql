CREATE TABLE `cat` 
(
  `catid` int(10) NOT NULL auto_increment,
  `pcatid` int(10) NOT NULL default '0',
  `catname` varchar(100) NOT NULL default '',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` varchar(250) NOT NULL default '',
  `catcode` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `inventory` 
(
  `inventoryid` int(10) NOT NULL auto_increment,
  `skuid` int(10) NOT NULL default '0',
  `qty` int(11) NOT NULL default '0',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedat` datetime NOT NULL,
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `itemid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`inventoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `item` 
(
  `itemid` int(10) NOT NULL auto_increment,
  `itemno` varchar(20) NOT NULL default '',
  `itemname` varchar(100) NOT NULL default '',
  `unitcost` decimal(11,2) NOT NULL default '0.00',
  `unitprice` decimal(11,2) NOT NULL default '0.00',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `sizes` varchar(250) NOT NULL default '',
  `colors` varchar(250) NOT NULL default '',
  `catid` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `itemsizecolor` 
(
  `sizecolorid` bigint(20) NOT NULL auto_increment,
  `itemid` int(10) NOT NULL default '0',
  `size` varchar(10) NOT NULL default '',
  `color` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`sizecolorid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `itemstatus` 
(
  `statusid` int(11) NOT NULL auto_increment,
  `statusname` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`statusid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `purchasedetail` 
(
  `purchasedetailid` bigint(20) NOT NULL auto_increment,
  `purchaseid` int(11) NOT NULL default '0',
  `skuid` int(10) NOT NULL default '0',
  `qty` int(11) NOT NULL default '0',
  `unitcost` decimal(11,2) default NULL,
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `seq` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`purchasedetailid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `purchaseheader` 
(
  `purchaseid` int(10) NOT NULL auto_increment,
  `txdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `supplier` varchar(100) NOT NULL default '',
  `totalqty` int(11) NOT NULL default '0',
  `totalamount` decimal(11,2) NOT NULL default '0.00',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `pono` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`purchaseid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `salesdetail` 
(
  `salesdetailid` bigint(20) NOT NULL auto_increment,
  `salesid` int(11) NOT NULL default '0',
  `skuid` int(10) NOT NULL default '0',
  `qty` int(11) NOT NULL default '0',
  `unitprice` decimal(11,2) NOT NULL default '0.00',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `seq` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`salesdetailid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `salesheader` 
(
  `salesid` int(10) NOT NULL auto_increment,
  `txdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `customer` varchar(100) NOT NULL default '',
  `totalqty` int(11) NOT NULL default '0',
  `totalamount` decimal(11,2) NOT NULL default '0.00',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `sono` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`salesid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `settings` 
(
  `isettingid` int(10) unsigned NOT NULL auto_increment,
  `sname` varchar(100) NOT NULL default '',
  `svalue` text NOT NULL,
  `icreatedby` int(10) unsigned NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) unsigned NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`isettingid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `sku` 
(
  `skuid` bigint(20) NOT NULL auto_increment,
  `itemid` int(10) NOT NULL default '0',
  `size` varchar(10) NOT NULL default '',
  `color` varchar(10) NOT NULL default '',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`skuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `stockadjdetail` 
(
  `stockadjdetailid` bigint(20) NOT NULL auto_increment,
  `stockadjid` int(11) NOT NULL default '0',
  `skuid` int(10) NOT NULL default '0',
  `qty` int(11) NOT NULL default '0',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `seq` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`stockadjdetailid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `stockadjheader` 
(
  `stockadjid` int(10) NOT NULL auto_increment,
  `txdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `stockadjno` varchar(20) NOT NULL default '',
  `remark` varchar(250) NOT NULL default '',
  `adjtype` int(11) NOT NULL default '0',
  `totalqty` int(11) NOT NULL default '0',
  PRIMARY KEY  (`stockadjid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `stockadjtypes` 
(
  `adjtype` int(11) NOT NULL default '0',
  `adjtypename` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`adjtype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `tmp_closing` 
(
  `tmpid` bigint(20) NOT NULL auto_increment,
  `skuid` int(11) NOT NULL default '0',
  `lasttxid` int(11) default NULL,
  `userid` int(11) NOT NULL default '0',
  `itemno` varchar(20) NOT NULL default '',
  `itemname` varchar(100) NOT NULL default '',
  `catid` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`tmpid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `tmp_transactions` 
(
  `tmptxid` bigint(20) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `itemid` int(11) NOT NULL default '0',
  `skuid` int(10) NOT NULL default '0',
  `openqty` int(11) NOT NULL default '0',
  `purchaseqty` int(11) NOT NULL default '0',
  `salesqty` int(11) NOT NULL default '0',
  `adjqty` int(11) NOT NULL default '0',
  `itemno` varchar(20) NOT NULL default '',
  `itemname` varchar(100) NOT NULL default '',
  `catid` int(11) NOT NULL default '0',
  `txdate` datetime default NULL,
  PRIMARY KEY  (`tmptxid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `transactions` 
(
  `txid` int(10) NOT NULL auto_increment,
  `txdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `skuid` int(10) NOT NULL default '0',
  `openqty` int(11) NOT NULL default '0',
  `purchaseqty` int(11) NOT NULL default '0',
  `salesqty` int(11) NOT NULL default '0',
  `adjqty` int(11) NOT NULL default '0',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedat` datetime NOT NULL,
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `itemid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`txid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `user` 
(
  `iuserid` int(10) NOT NULL auto_increment,
  `sloginid` varchar(30) NOT NULL default '',
  `spassword` varchar(32) NOT NULL default '',
  `susername` varchar(100) NOT NULL default '',
  `sstatus` char(1) NOT NULL default '',
  `icreatedby` int(10) NOT NULL default '0',
  `dcreatedby` datetime NOT NULL default '0000-00-00 00:00:00',
  `imodifiedby` int(10) NOT NULL default '0',
  `dmodifiedby` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`iuserid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;