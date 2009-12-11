<?php

########################################################################
# Extension Manager/Repository config file for ext: "pt_gsaarticlelist"
#
# Auto generated 11-03-2009 12:37
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'GSA Article List',
	'description' => 'Displays article lists for GSA Shop',
	'category' => 'General Shop Applications',
	'author' => 'Fabrizio Branca',
	'author_email' => 't3extensions@punkt.de',
	'shy' => '',
	'dependencies' => 'cms,pt_tools,pt_gsashop',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'punkt.de GmbH',
	'version' => '0.0.4dev',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'pt_tools' => '0.4.1-',
			'pt_gsashop' => '0.14.0-',
			'php' => '5.1.0-0.0.0',
			'typo3' => '4.1.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:24:{s:10:".cvsignore";s:4:"9538";s:9:"ChangeLog";s:4:"b7a8";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"4546";s:17:"ext_localconf.php";s:4:"48a3";s:14:"ext_tables.php";s:4:"5bdc";s:16:"locallang_db.xml";s:4:"6a45";s:14:"doc/DevDoc.txt";s:4:"145d";s:14:"doc/manual.sxw";s:4:"5066";s:19:"doc/wizard_form.dat";s:4:"e69d";s:20:"doc/wizard_form.html";s:4:"0e8e";s:37:"pi1/class.tx_ptgsaarticlelist_pi1.php";s:4:"b2b1";s:19:"pi1/flexform_ds.xml";s:4:"de5a";s:17:"pi1/locallang.xml";s:4:"b6a6";s:21:"pi1/locallang_tca.xml";s:4:"5707";s:24:"pi1/static/constants.txt";s:4:"820c";s:24:"pi1/static/editorcfg.txt";s:4:"83a9";s:20:"pi1/static/setup.txt";s:4:"53bd";s:53:"res/class.tx_ptgsaarticlelist_defaultDataProvider.php";s:4:"6f08";s:49:"res/class.tx_ptgsaarticlelist_getDataProvider.php";s:4:"bdff";s:47:"res/class.tx_ptgsaarticlelist_iDataProvider.php";s:4:"be65";s:17:"res/locallang.xml";s:4:"109b";s:30:"res/smarty_tpl/articlelist.tpl";s:4:"4c0c";s:24:"res/smarty_tpl/pager.tpl";s:4:"f2d9";}',
	'suggests' => array(
	),
);

?>