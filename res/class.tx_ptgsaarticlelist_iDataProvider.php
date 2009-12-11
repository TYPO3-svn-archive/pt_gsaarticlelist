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
 * Interface for a data provider object for the article list plugin
 *
 * $Id: class.tx_ptgsaarticlelist_iDataProvider.php,v 1.2 2008/03/05 12:54:51 ry44 Exp $
 *  
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-03-05
 */
interface tx_ptgsaarticlelist_iDataProvider {



	/**
	 * Init method will be called before rendering the page
	 *
	 * @param 	tx_ptgsaarticlelist_pi1 $ref
	 * @return 	void
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-03-05
	 */
	public function init(tx_ptgsaarticlelist_pi1 $ref);



	/**
	 * Get article collection for a page
	 *
	 * @param 	int	offset
	 * @param 	int	row count
	 * @return 	tx_ptgsashop_articleCollection
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-03-05
	 */
	public function getArticleCollectionForPage($offset, $rowcount);



	/**
	 * Get total article count
	 *
	 * @param 	void
	 * @return 	int total article count
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-03-05
	 */
	public function getArticleCount();



	/**
	 * Get headline
	 * 
	 * @param 	void
	 * @return 	string	headline 
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-03-05
	 */
	public function getHeadline();

}

?>