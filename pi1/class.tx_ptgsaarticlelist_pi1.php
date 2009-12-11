<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007-2008 Fabrizio Branca <branca@punkt.de>
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
 * Inclusion of TYPO3 libraries
 *
 * @see tslib_pibase
 */
require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_gsashop').'res/class.tx_ptgsashop_lib.php';  // GSA shop library class with static methods
require_once t3lib_extMgm::extPath('pt_gsashop').'res/class.tx_ptgsashop_article.php';  // GSA shop article class
require_once t3lib_extMgm::extPath('pt_gsashop').'res/class.tx_ptgsashop_articleFactory.php';  // GSA shop article factory class
require_once t3lib_extMgm::extPath('pt_gsashop').'res/class.tx_ptgsashop_cart.php';  // GSA shop cart class
require_once t3lib_extMgm::extPath('pt_gsashop').'res/class.tx_ptgsashop_sessionFeCustomer.php';  // GSA shop frontend customer class

require_once t3lib_extMgm::extPath('pt_gsashop').'pi2/class.tx_ptgsashop_pi2.php';  // GSA shop library class with static methods

/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_smartyAdapter.php';  // Smarty template engine adapter
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_formReloadHandler.php'; // web form reload handler class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_sessionStorageAdapter.php'; // storage adapter for TYPO3 _browser_ sessions


/**
 * Provides a frontend plugin displaying an article infobox with online order button for a specified article from the GSA database
 *
 * @author      Fabrizio Branca <branca@punkt.de>
 * @since       2007-10-30
 * @package     TYPO3
 * @subpackage  tx_ptgsaarticlelist
 */
class tx_ptgsaarticlelist_pi1 extends tx_ptgsashop_pi2 {
    
    /**
     * tslib_pibase (parent class) instance variables
     */
    public $prefixId      = 'tx_ptgsaarticlelist_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_ptgsaarticlelist_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'pt_gsaarticlelist';	// The extension key.
	
	/**
	 * @var 	tslib_cObj	
	 */
	public $cObj;
    
    /**
     * tx_ptgsaarticlelist_pi1 instance variables
     */
    // protected $extConfArr = array();      // (array) basic extension configuration data from localconf.php (configurable in Extension Manager)
    
    protected $formActionSelf = '';       // (string) address for HTML forms' 'action' attributes to send a form of this page to itself
    
	/**
     * @var tx_pttools_formReloadHandler	web form reload handler object
     */ 
    protected $formReloadHandler = NULL; 

    /**
     * @var tx_ptgsashop_cart 	shopping cart object
     */
    protected $cartObj = NULL;          

    /**
     * @var tx_ptgsashop_sessionFeCustomer	frontend customer object (FE user who uses this plugin)
     */   
    protected $customerObj = NULL;       
    
	/**
     * @var bool	flag wether this plugin is called by a FE user who is legitimated to use net prices (0=gross prices, 1=net prices)
     */
    protected $isNetPriceDisplay = 0; 
    
    /**
     * @var tx_ptgsashop_articleCollection	article collection for the current page
     */
    protected $artCollForCurrentPage; 
    
    /**
     * @var int		total amount of articles (over all pages) for this view
     */
    protected $totalArticleAmount;
    
    
    /**
     * Class Constants
     */
    const ARTICLECONFIRMATION_CLASS_NAME = 'tx_ptgsashop_pi1'; // (string) class name of the article confirmation plugin to use combined with this plugin (if configured in Constant Editor)
    
    
    
    /***************************************************************************
     *   MAIN
     **************************************************************************/
    
    /** 
     * Main method of the plugin: Prepares properties and instances, interprets submit buttons to control plugin behaviour and returns the page content
     *
     * @param   string      HTML-Content of the plugin to be displayed within the TYPO3 page
     * @param   array       Global configuration for this plugin (mostly done in Constant Editor/TS setup)
     * @return  string      HTML plugin content for output on the page (if not redirected before)
     * @global  integer     $GLOBALS['TSFE']->id: UID of the current page
     * @author  Fabrizio Branca <branca@punkt.de>
     * @since   2007-10-30
     */
    public function main($content, $conf) {
           
        // ********** DEFAULT PLUGIN INITIALIZATION **********
        $this->conf = $conf; // Extension configuration (mostly taken from Constant Editor)
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
        $this->pi_USER_INT_obj = 1; // Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
        
        // for TYPO3 3.8.0+: enable storage of last built SQL query in $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery for all query building functions of class t3lib_DB
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;
            
        // debug tools for development (uncomment to use)
        # tx_ptgsashop_lib::clearSession(__FILE__, __LINE__); // unset all session objects
        trace($this->conf, 0, '$this->conf'); // extension config data (mainly from TS setup/Constant Editor)
        trace($this->piVars, 0, '$this->piVars');
        #trace($this->cObj->data, 0, '$this->cObj->data'); // content element data, containing tx_ptgsashop_* plugin config
            
        // get basic extension configuration data from localconf.php (configurable in Extension Manager) - HAS TO BE PLACED BEFORE 'date_default_timezone_set()'!
        // $this->extConfArr = tx_pttools_div::returnExtConfArray($this->extKey);
        
        // set the timezone (see http://php.net/manual/en/timezones.php) since it is not safe to rely on the system's timezone settings
        // date_default_timezone_set($this->extConfArr['timezone']);
        
       
        
        
        try {
            
            // ********** SET PLUGIN-OBJECT PROPERTIES **********
            
            // get conf array/flexform values and override regular typoscript configuration (added by Fabrizio Branca 2007/12/11)
            try {
				tx_pttools_div::mergeConfAndFlexform($this);
			} catch (tx_pttools_exception $excObj) {
				// do nothing if no flexform exists (e.g. when calling directly by typoscript...)
				trace($excObj->__toString(), 0, 'No flexform configuration found');
			}
			
            $this->formActionSelf    = $this->pi_getPageLink($GLOBALS['TSFE']->id); // set self url for HTML form action attributes
            $this->formReloadHandler = new tx_pttools_formReloadHandler; // set form reload handler object
            $this->cartObj = tx_ptgsashop_cart::getInstance(); // get unique instance (Singleton) of shopping cart
            $this->customerObj = tx_ptgsashop_sessionFeCustomer::getInstance(); // get unique instance (Singleton) of current FE customer
            $this->isNetPriceDisplay = $this->customerObj->getNetPriceLegitimation(); // set flag wether this plugin has been called by a FE user who is legitimated to use net prices
                        
            $this->piVars['page'] = isset($this->piVars['page']) ? $this->piVars['page'] : 1;
            
            if (!empty($this->piVars['gsa_id'])){
                $this->articleObj = tx_ptgsashop_articleFactory::createArticle(           
                                        $this->piVars['gsa_id'], 
                                        $this->customerObj->get_priceCategory(), 
                                        $this->customerObj->get_gsaMasterAddressId(), 
                                        1, 
                                        '',  
                                        $this->conf['display_img']
                                    );
            }
            
            // ********** CONTROLLER: execute approriate method for any action command (retrieved form buttons/GET vars) **********
            
            // [CMD] add to cart: if appropriate button has been used for the plugin related article: store article in shopping cart
            if (isset($this->piVars['cart_button'])) { 
                $content .= $this->exec_addArticleToCart();
            // [CMD] remove from cart: if appropriate button has been used for the plugin related article: remove article from shopping cart
            } elseif (isset($this->piVars['remove_button'])) { 
                $content .= $this->exec_deleteArticleFromCart();
            // [CMD] Default action: get quantity of article in current shopping cart and display article infobox
            } else {                
                $content .= $this->exec_defaultAction();
            }
            
            
            // ********** DEFAULT PLUGIN FINALIZATION ********** 
            
            $this->cartObj->store(); // save current shopping cart to session
            
            
        } catch (tx_pttools_exception $excObj) {
            
            // if an exception has been catched, handle it and overwrite plugin content with error message
            $excObj->handleException();
            $content = '<i>'.$excObj->__toString().'</i>';
            
        }
        
        
        
        return $this->pi_wrapInBaseClass($content);
        
        
    } // end fuction main
    
    
    
        
    
    
    /***************************************************************************
     *   BUSINESS LOGIC METHODS: CONTROLLER ACTIONS
     **************************************************************************/
     
    /**
     * Controller default action: get quantity of article in current shopping cart and display article infobox
     *
     * @param   boolean     flag whether a redirect to the current page should be executed (true: enables other plugins placed above this one to process the results of this plugin's actions) or if the plugin should be displayed without redirect (false: default)
     * @return  string      HTML plugin content for output on the page
     * @global  integer     $GLOBALS['TSFE']->id: UID of the current page
     * @author  Fabrizio Branca <branca@punkt.de>
     * @since   2007-10-30 (based on Rainer Kuhn's code in tx_ptgsashop_pi2)
     */
    protected function exec_defaultAction($selfRedirect = false) {
        
        $content = '';
        trace('[CMD] '.__METHOD__);
                   
        // save current page id to session (used for possible return from shopping cart)
        tx_pttools_sessionStorageAdapter::getInstance()->store(tx_ptgsashop_lib::SESSKEY_LASTORDERPAGE, $GLOBALS['TSFE']->id);
        
        // redirect to the current page enables other plugins placed above this one to process the session results of this plugin's actions
        if ($selfRedirect == true) {
            $this->cartObj->store(); // save current shopping cart to session
            tx_pttools_div::localRedirect($this->formActionSelf);
        }
        
        if ($this->conf['paging']){
            $offset = (($this->conf['paging']) * ($this->piVars['page'] -1 ));
            $rowcount = $this->conf['paging'];
        } else {
            $offset = '';
            $rowcount = ($this->conf['limit']) ? $this->conf['limit'] : '';    
        }
        
        
        if (empty($this->conf['getArticleCollectionHook']) || $this->conf['getArticleCollectionHook'] == 'default') {
            $this->conf['getArticleCollectionHook'] = 'EXT:pt_gsaarticlelist/res/class.tx_ptgsaarticlelist_defaultDataProvider.php:tx_ptgsaarticlelist_defaultDataProvider'; 
        }
        
        $dataProviderObj = t3lib_div::getUserObj($this->conf['getArticleCollectionHook'], '');
        $dataProviderObjClass = get_class($dataProviderObj);
        
        if (!is_object($dataProviderObj) || !in_array('tx_ptgsaarticlelist_iDataProvider', class_implements($dataProviderObjClass)) ) {
            throw new tx_pttools_exception ('No valid data provider', 0, 'Class of the data provider object ('.$dataProviderObjClass.') does not implement the tx_ptgsaarticlelist_iDataProvider interface');
        }
        
        $dataProviderObj->init($this);
        
        $this->artCollForCurrentPage = $dataProviderObj->getArticleCollectionForPage($offset, $rowcount);
        $this->totalArticleAmount = $dataProviderObj->getArticleCount();        
        
	    $pager = ($this->conf['paging'] > 0) ? $this->displayPager($this->totalArticleAmount, $this->conf['paging'], $this->piVars['page'], $this->conf['templateFilePager']) : '';
	    
	    $pager = $this->cObj->stdWrap($pager, $this->conf['pager_stdWrap.']);
        
	    $headline = $dataProviderObj->getHeadline();
	    
	    $headline = $this->cObj->stdWrap($headline, $this->conf['headline_stdWrap.']);
	    
        // add article infobox with order button to the page's content  
        $content .= $headline . $pager . $this->displayArticleCollection($this->artCollForCurrentPage, $this->conf['templateFileArticleList']) . $pager;
        
        return $content;
        
    }
    
    
	
	/**
	 * Displays an article collection
	 *
	 * @param 	tx_ptgsashop_articleCollection $artColl
	 * @param 	string	path to smarty template
	 * @return	string	HTML output
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2007-10-30
	 */
	protected function displayArticleCollection(tx_ptgsashop_articleCollection $artColl, $template){
	    
	    $markerArray = array();
	    
	    /* @var $articleObj tx_ptgsashop_article */
	    foreach ($artColl as $articleObj){
	        
	        $cartArticleObj = $this->cartObj->getItem($articleObj->get_id());
	        $cartArticleQty = (integer)(isset($cartArticleObj) && is_object($cartArticleObj) ? $cartArticleObj->get_quantity() : 0);
	        
	        $markerArray['articles'][] = $this->getMarkerArrayFromArticle($articleObj, $cartArticleQty);
	    }
	    
        $smarty = new tx_pttools_smartyAdapter($this);
        foreach ($markerArray as $markerKey => $markerValue) {
            $smarty->assign($markerKey, $markerValue);
        }
        
        $filePath = $smarty->getTplResFromTsRes($template);
        trace($filePath, 0, 'Smarty template resource $filePath');
        return $smarty->fetch($filePath);   
	}
	
	
	
	/**
	 * Displays the pager
	 *
	 * @param 	int		amount of total items
	 * @param 	int		amount of items per page
	 * @param 	int		page number of the current page
	 * @param 	string	path to the smarty template
	 * @return 	string	HTML Output
	 * @author 	Fabrizio Branca <branca@punkt.de>
	 * @since 	2008-01
	 */
    protected function displayPager($totalitems, $itemsperpage, $thispage, $template){
        $smarty = new tx_pttools_smartyAdapter($this);
        
        $amountPages = ceil($totalitems / $itemsperpage);
        
        if ($amountPages > 1) {
        
            $markerArray = array();
            
            $markerArray['fill'] = $this->pi_getLL('fill');
            
            for($i = 1; $i<=$amountPages; $i++){
                if (abs($i-$thispage) <= $this->conf['pagerDelta'] || ($i == 1) || ($i==$amountPages) ) {
                    
                    if ($i == $thispage) {
                        $class = 'current';
                    } elseif ($i == 1) {
                        $class = 'first';
                    } elseif ($i == $amountPages) {
                        $class = 'last';
                    } else {
                        $class = '';
                    }
                    
                    $tmpTypolinkConf = $this->conf['pager_typolink.'];
		    	    $tmpTypolinkConf['additionalParams'] .= '&'.$this->prefixId.'[page]='.$i;
		    	    $url = $this->cObj->typoLink('', $tmpTypolinkConf);
                    
                    $markerArray['pages'][] = array ('label' => $i,
                                                     'url' =>  $url, // $this->pi_linkTP_keepPIvars_url(array('page' => $i)),
                    								 'class' => $class
                                                    );
                } elseif (end($markerArray['pages']) != 'fill') {
                    $markerArray['pages'][] = 'fill';
                }
                
            }
            
            // "previous page" link
            $prevpage = max($thispage-1, 1);
            
            $tmpTypolinkConf = $this->conf['pager_typolink.'];
    	    $tmpTypolinkConf['additionalParams'] .= '&'.$this->prefixId.'[page]='.$prevpage;
    	    $url = $this->cObj->typoLink('', $tmpTypolinkConf);
            
            $markerArray['prev'] = array (   'label' => $this->pi_getLL('prev'),
                                             'url' => $url, //  $this->pi_linkTP_keepPIvars_url(array('page' => $prevpage)),
            								 'class' => 'prev',
            								 'alreadyhere' => ($prevpage == $thispage)
                                            );
    	
    		// "next page" link
            $nextpage = min($thispage+1, $amountPages);
            
            $tmpTypolinkConf = $this->conf['pager_typolink.'];
    	    $tmpTypolinkConf['additionalParams'] .= '&'.$this->prefixId.'[page]='.$nextpage;
    	    $url = $this->cObj->typoLink('', $tmpTypolinkConf);
    	    
            $markerArray['next'] = array (	 'label' => $this->pi_getLL('next'),
                                             'url' =>  $url, // $this->pi_linkTP_keepPIvars_url(array('page' => $nextpage)),
                                    		 'class' => 'next',
            								 'alreadyhere' => ($nextpage == $thispage)
                                            );
            
            foreach ($markerArray as $markerKey => $markerValue) {
                $smarty->assign($markerKey, $markerValue);
            }
            
            $filePath = $smarty->getTplResFromTsRes($template);
            trace($filePath, 0, 'Smarty template resource $filePath');
            return $smarty->fetch($filePath);
        } else {
            return ''; // no pager   
        }
    }
        
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsaarticlelist/pi1/class.tx_ptgsaarticlelist_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsaarticlelist/pi1/class.tx_ptgsaarticlelist_pi1.php']);
}

?>
