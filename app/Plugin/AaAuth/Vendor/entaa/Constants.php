<?php

/**
 * Department of Administrative Services
 * Information Technology Enterprise
 *
 * ITE PHP Enterprise A&A Client
 *
 * This source file is subject to version 2.02 of the PHP license, that is bundled with this 
 * package in the file LICENSE, and is available at through the world-wide-web at
 * http://www.php.net/license/2_02.txt. If you did not receive a copy of the PHP license and are 
 * unable to obtain it through the world-wide-web, please send a note to license@php.net so we can 
 * mail you a copy immediately.
 *
 * @author Tony Bibbs <tony.bibbs@iowa.gov>
 * @package gov.iowa.das.ite.enterprise.aa.common
 * @copyright 2003-2005
 * @version $Id: Constants.php,v 1.2 2006/04/24 14:39:12 jcarlson Exp $
 *
 */

/**
 * This file contains result code constants. The result codes are the list of possible exceptions 
 * the server may generate.
 *
 * @package gov.iowa.das.ite.aaclient
 *
 */

/**
 * This identifies the privilege that gives a user full
 * access to this A&A system.  Give this privilege 
 * sparingly!
 * @const AA_ADMIN_PRIV
 */
define('AA_ADMIN_PRIV', 'AA_ADMIN');

/**
 * Denotes a successful action
 * @const SUCCESSFUL_RETURN
 */
define('SUCCESSFUL_RETURN',0);

/**
 * Denotes the user account doesn't exists
 * @const INVALID_USER_EXCEPTION
 */
define('INVALID_USER_EXCEPTION',1);

/**
 * Denotes the user account is locked
 * @const ACCOUNT_LOCKED_EXCEPTION
 */
define('ACCOUNT_LOCKED_EXCEPTION',2);

/**
 * Denotes a problem with changing the user's password
 * @const CHANGE_PASSWORD_EXCEPTION
 */
define('CHANGE_PASSWORD_EXCEPTION',3);

/**
 * Denotes not enough data was passed to the server
 * @const INSUFFICIENT_DATA_EXCEPTION
 */
define('INSUFFICIENT_DATA_EXCEPTION',4);

/**
 * New password was rejected
 * @const INVALID_NEW_PASSWORD_EXCEPTION
 */
define('INVALID_NEW_PASSWORD_EXCEPTION',5);

/**
 * Denotes the account used doesn't have sufficent rights
 * @const ADMIN_PERMISSION_EXCEPTION
 */
define('ADMIN_PERMISSION_EXCEPTION',6);

/**
 * Dentoes the admin account used is locked
 * @const ADMIN_ACCOUNT_LOCKED_EXCEPTION
 */
define('ADMIN_ACCOUNT_LOCKED_EXCEPTION',7);

/**
 * Denotes user didn't have the needed privilege
 * @const INVALID_PRIVILEGE_EXCEPTION
 */
define('INVALID_PRIVILEGE_EXCEPTION',8);

/**
 * Denotes the user's password has expired
 * @const PASSWORD_EXPIRED_EXCEPTION
 */
define('PASSWORD_EXPIRED_EXCEPTION',9);

/**
 * Denotes the user's password has expired
 * @const ACCOUNT_DISABLED_EXCEPTION
 */
define('ACCOUNT_DISABLED_EXCEPTION',10);

/**
 * Denotes the user's password has expired
 * @const ADMIN_ACCOUNT_DISABLED_EXCEPTION
 */
define('ADMIN_ACCOUNT_DISABLED_EXCEPTION',11);

/**
 * Denotes the user's password has expired
 * @const ADMIN_ACCOUNT_NOT_INITIALIZED_EXCEPTION
 */
define('ADMIN_ACCOUNT_NOT_INITIALIZED_EXCEPTION',12);

/**
 * Denotes the user's password has expired
 * @const INVALID_TOKEN_EXCEPTION
 */
define('INVALID_TOKEN_EXCEPTION',13);

/**
* Some sort of unexpected error occured
* @const UNEXPECTED_FATAL_EXCEPTION
*/
define('UNEXPECTED_FATAL_EXCEPTION',100);

?>