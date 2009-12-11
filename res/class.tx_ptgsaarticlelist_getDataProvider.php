<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Fabrizio Branca (branca@punkt.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * Class 'tx_ptgsaarticlelist_getDataProvider'
 * 
 * $Id: class.tx_ptgsaarticlelist_getDataProvider.php,v 1.3 2008/03/25 10:38:19 ry44 Exp $
 *
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since   2008-02-06
 */	
class tx_ptgsaarticlelist_getDataProvider {
    
	
	/**
	 * Get available data providers registred by an hook
	 *
	 * @param	array	configuration array
	 * @return 	array	configuration array
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-03-05
	 */
    public function getAvailDataProvider(array $config) {
		
		if (is_array($GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS'][t3lib_extMgm::extPath('pt_gsaarticlelist').'res/class.tx_ptgsaarticlelist_getDataProvider.php']['availableDataProvider'])){
			foreach($GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS'][t3lib_extMgm::extPath('pt_gsaarticlelist').'res/class.tx_ptgsaarticlelist_getDataProvider.php']['availableDataProvider'] as $dataProviderArray){
				$config['items'][] = array ($GLOBALS['LANG']->sL($dataProviderArray['name']), $dataProviderArray['path']);
			}
		}
        return $config;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsaarticlelist/res/class.tx_ptgsaarticlelist_getDataProvider.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsaarticlelist/res/class.tx_ptgsaarticlelist_getDataProvider.php']);
}

?>