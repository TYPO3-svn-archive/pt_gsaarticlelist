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

require_once t3lib_extMgm::extPath('pt_gsaarticlelist') . 'res/class.tx_ptgsaarticlelist_iDataProvider.php';



/**
 * Default data provider class. This data provider will be user if no other is selected. It simply shows all articles
 * 
 * $Id: class.tx_ptgsaarticlelist_defaultDataProvider.php,v 1.8 2008/10/15 11:03:51 ry37 Exp $
 *
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-03-05
 */
class tx_ptgsaarticlelist_defaultDataProvider extends tx_ptgsaarticlelist_pi1 implements tx_ptgsaarticlelist_iDataProvider {
    
    /**
     * @var tx_ptgsaarticlelist_pi1	plugin object
     */
    protected $pObj;



    /**
     * Init method will be called before rendering the page
     *
     * @param 	tx_ptgsaarticlelist_pi1 $ref
     * @return 	void
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-03-05
     */
    public function init(tx_ptgsaarticlelist_pi1 $ref) {

        $this->pObj = $ref;
    
    }



    /**
     * Get article collection for a page
     *
     * @param 	int	offset
     * @param 	int	row count
     * @return 	tx_ptgsashop_articleCollection
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-03-05
     */
    public function getArticleCollectionForPage($offset, $rowcount) {

        $limit = (($offset != '') ? $offset . ',' : '') . $rowcount;
        
        $orderBy = $this->pObj->cObj->stdWrap($this->pObj->conf['defaultDataProvider.']['orderBy'], $this->pObj->conf['defaultDataProvider.']['orderBy.']);
        $searchString = $this->pObj->cObj->stdWrap($this->pObj->conf['defaultDataProvider.']['searchString'], $this->pObj->conf['defaultDataProvider.']['searchString.']);
        $andWhere = $this->pObj->cObj->stdWrap($this->pObj->conf['defaultDataProvider.']['andWhere'], $this->pObj->conf['defaultDataProvider.']['andWhere.']);
        
        $onlineArticlesArr = tx_ptgsashop_articleAccessor::getInstance()->selectOnlineArticles($orderBy, $limit, $searchString, $andWhere);
        
        $artColl = new tx_ptgsashop_articleCollection();
        
        foreach ($onlineArticlesArr as $onlineArticle) {
            $artColl->addItem(tx_ptgsashop_articleFactory::createArticle($onlineArticle['NUMMER'], $this->pObj->customerObj->get_priceCategory(), $this->pObj->customerObj->get_gsaMasterAddressId(), 1, '', $this->pObj->conf['articleDisplayImg']));
        }
        return $artColl;
    
    }



    /**
     * Get total article count
     *
     * @param 	void
     * @return 	int total article count
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-03-05
     */
    public function getArticleCount() {

        return tx_ptgsashop_articleAccessor::getInstance()->selectOnlineArticlesQuantity();
    
    }



    /**
     * Get headline
     * 
     * @param 	void
     * @return 	string	headline 
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-03-05
     */
    public function getHeadline() {

        return $this->ll('defaultDataProvider_headline');
    
    }



    /**
     * Helper function: Get language label
     *
     * @param   string  key
     * @return  string  label
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-02-15
     */
    public function ll($key) {
    	return $this->pObj->pi_getLL($key);
    }
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsaarticlelist/res/class.tx_ptgsaarticlelist_defaultDataProvider.php']) {
    include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsaarticlelist/res/class.tx_ptgsaarticlelist_defaultDataProvider.php']);
}

?>