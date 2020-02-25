<?php

/* Reminder: always indent with 4 spaces (no tabs). */

/**
 * Department of Administrative Services
 * Information Technology Enterprise
 *
 * ITE PHP5 Enterprise Enterprise Authentication and Authorization (A&A) Client
 *
 * This source file is subject to version 2.02 of the PHP license, that is bundled with this 
 * package in the file LICENSE, and is available at through the world-wide-web at
 * http://www.php.net/license/2_02.txt. If you did not receive a copy of the PHP license and are 
 * unable to obtain it through the world-wide-web, please send a note to license@php.net so we can 
 * mail you a copy immediately.
 *
 * @author Tony Bibbs <tony.bibbs@iowa.gov>
 * @copyright 2005 State of Iowa
 * @version $Id: Client.php,v 1.12 2007/01/16 21:04:39 justincarlson Exp $
 *
 */

/**
 * The A&A service API
 */
require_once 'ServiceInterface.php';

/**
 * This file defines the application program interface into ITE's Enterprise A&A Service
 *
 * @package gov.iowa.das.ite.aaclient
 *
 */
class ITE_AAClient_Client implements ITE_AAClient_ServiceInterface  {
    /**
     * Holds the configured A&A settings
     * @var array
     * @access private
     */
    private $options = array();
    
    /**
     * Holds instance of the handler we should use to talk to the A&A Service
     * @var string
     * @access private
     */
    private $handler = null;
        
    /**
     * Constructor
     *
     * Configure the A&A client so that all subsequent calls are more convenient.
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public 
     * @param string $handler Method of communication to use, can use.  Only XML is supported right 
     * now.  SOAP support will be added.
     * @param string $appID Application ID that was assigned to the application from the A&A 
     * Service.  Customers must contact ITE to set-up application ID's for new A&A enabled
     * applications.
     * @param string $aaServer Host name of the A&A Server
     * @param int $aaPort Port to talk over on A&A Server
     * @param boolean $useSSL Whether or not we should talk over SSL to the A&A Service
     * @return void
     * @todo Eventually the A&A service will be expanded to support SOAP.  when that happens, this
     * method should still work fine, it's only that the handler given would change.  This is an 
     * FYI.
     *
     */
    public function __construct($handler, $appId, $aaServer, $aaPath = '/entaa/', $useSSL=true, 
        $aaPort='')
    {

        $this->options = array('appId'=>$appId,'aaServer'=>$aaServer,'aaPath'=>$aaPath,
            'useSSL'=>$useSSL, 'aaPort'=>$aaPort);
        // Instantiate the right handler.
        $handlerFileName = sprintf('%sHandler.php', $handler);
        require_once $handlerFileName;
        $handlerClass = sprintf('ITE_AAClient_%sHandler', $handler);        
        $this->handler = new $handlerClass($appId, $aaServer, $aaPath, $useSSL, $aaPort);
         
    }
    
    /**
     * Authenticates a user
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $userId ID for the user logging in
     * @param string $password Password to authenticate user with
     * @return ITE_AAClient_User A&A user object that is serializable
     *
     */
    public function &authenticate($userId, $password, $providerId = '')
    {
        $userObj = &$this->handler->authenticate($userId, $password, $providerId);
        
        // That's right, we are sending this object as part of the user object.  All methods on the
        // user object simply proxy back here and we proxy on to the handler.
        $userObj->setHandler($this);
        
        return $userObj;
    }
    
        /**
     * Gets password rules
     *
     * @author Brad Shutters
     * @access public
     * @param string $providerId ID of provider to use
     * @return string representing password rules.
     */
    public function getPasswordRules($providerId = '')
    {
        return $this->handler->getPasswordRules($providerId);
    }
    
    /**
     * Creates a new Enterprise A&A account in the repository tied to the application
     *
     * NOTE: this method does not work yet on the Active Directory provider.
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $userId ID to assign to the new user
     * @param string $password Initial password for the user
     * @param string $fname User's first name
     * @param string $lname User's last name
     * @param string $email User's email address
     * @param array $privArray Array of privileges to assign to the user
     *
     */
    public function createAccount($userId, $password, $fname, $lname, $email='', $privArray='')
    {
        if (empty($privArray)) {
            $privArray = array();
        }
        $this->handler->createAccount($userId, $password, $fname, $lname, $email, $privArray);
    }

    /**
     * Creates a new Enterprise A&A account in the repository tied to the application with an application user as the authority
     *
     * NOTE: this method does work
     *
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @access public
     * @param string $applicationUser admin/application id with user creation authority
     * @param string $applicationPass admin/application password
     * @param string $userId ID to assign to the new user
     * @param string $password Initial password for the user
     * @param string $fname User's first name
     * @param string $lname User's last name
     * @param string $email User's email address
     * @param array $privArray Array of privileges to assign to the user
     *
     */    
    public function createAccountViaAppUser($applicationUser, $applicationPass, $userId, $password, $fname, $lname, $email='', $privArray=''){
        if (empty($privArray)) {
            $privArray = array();
        }
        $this->handler->createAccountViaAppUser($applicationUser, $applicationPass, $userId, $password, $fname, $lname, $email, $privArray);
    }
    
    /** Updates a password for an Enterrise A&A Account
    *
    * Note: Changes a user's password
    *
    * @author Justin Carlson <justin.carlson@iowa.gov>
    * @access public
    * @param string $userId The user to be updated
    * @param string $oldPassword The user's current/old password
    * @param string $newPassword The user's new password
    */
    public function changePassword($userId, $oldPassword, $newPassword){
        $this->handler->changePassword($userId, $oldPassword, $newPassword);
    }    
    
     /**
     * Updates a user account
     *
     * @author Brad Shutters
     * @access public
     * @param string $userId User to update account for
     * @param string $password Password to authenticate with
     * @param string $fname First Name
     * @param string $lname Last Name
     * @param string $email Email Address
     * @param string $providerId Provider the user account exists in.  This is only needed for
     * those applications that support more than one provider
     *
     */
    public function updateAccount($userId, $password, $fname, $lname, $email, $providerId = '')
    {
        $this->handler->updateAccount($userId, $password, $fname, $lname, $email, $providerId);
    }
    
    /**
     * Allows admin to change a user's password
     *
     * This method allows an admin user ot change the password of a user to a particular value.
     *
     * @access public
     * @author Brad Shutters
     * @param    string      $adminUserId    User to  change password for
     * @param    string      $adminPassword  Current password
     * @param    string      $userId         User to  change password for
     * @param    string      $newPassword    New password
     *
     */
    public function changePasswordByAdmin($adminUserId, $adminPassword, $userId, $newPassword)
    {
        $this->handler->changePasswordByAdmin($adminUserId, $adminPassword, $userId, $newPassword);
    }
    
     /**
     * Resets a password
     *
     * This method allows an admin user to reset the password for the specified user.
     * Returns the new, randomly generated password
     *
     * @access public
     * @author Brad Shutters
     * @param string $adminUserId Admin performing the reset
     * @param string $adminPassword Admin's password
     * @param string $userId User to reset password for
     * @return string Radomly generated password
     *
     */
    public function resetPassword($adminUserId, $adminPassword, $userId)
    {
        return $this->handler->resetPassword($adminUserId, $adminPassword, $userId);
    }
    
        /**
     * Gets the user object from the A&A service tied to the given SSO token
     *
     * Enterprise A&A does support Single Sign-on. When an application uses SSO, we take them to 
     * the A&A SSO login page and when they successfully login they are returned to the calling
     * application and are passed the user's SSO token.  From there the calling application would
     * use this method to get the user object for the authenticated user
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access pulbic
     * @param string $ssoToken The SSO token tied to the user
     * @return ITE_AAClient_User A&A User object
     *
     */
    public function &getUserObject($ssoToken)
    {
        if (empty($ssoToken)) {
            require_once 'PEAR/Exception.php';
            throw new PEAR_Exception('You must give a valid SSO token');
        }
        
        $userObj = &$this->handler->getUserObject($ssoToken);
        
        // That's right, we are sending this object as part of the user object.  All methods on the
        // user object simply proxy back here and we proxy on to the handler.
        $userObj->setHandler($this);
        
        return $userObj;
    }
    
         /**
     * Allows an administrator to update a user account
     *
     * @author Brad Shutters
     * @access public
     * @param string $adminUserId Administrator's user ID
     * @param string $adminPassword Password to authenticate administrator with
     * @param string $userId User to update account for
     * @param string $password Password to authenticate with
     * @param string $fname First Name
     * @param string $lname Last Name
     * @param string $email Email Address
     * @param string $providerId Provider the user account exists in.  This is only needed for
     * those applications that support more than one provider
     *
     */
     public function updateAccountByAdmin($adminUserId, $adminPassword, $userId, $password, $fname, $lname, $email, $providerId = '')
     {
        return $this->handler->updateAccountByAdmin($adminUserId, $adminPassword, $userId, $password, $fname, $lname, $email, $providerId = '');
     }
    
     /**
     * Gets application priveliges for specified user
     *
     * This method retrieves the privilges of the specified user
     *
     * @access public
     * @author Brad Shutters
     * @param string $adminUserId Admin making the request
     * @param string $adminPassword Admin's password
     * @param string $userId User to get privileges for
     * @return AAPrivilegeInterface Object holding the user's privileges
     *
     */
     public function getUserPrivileges($adminUserId, $adminPassword, $userId)
     {
        return $this->handler->getUserPrivileges($adminUserId, $adminPassword, $userId);
     }
     
     /**
     * Sets the privileges for a given user and application
     *
     * @access public
     * @author Brad Shutters
     * @param string $adminUserId Admin making the request
     * @param string $adminPassword Admin's password
     * @param string $userId User to set privileges for
     * @param AAPrivilege[] $privileges Privileges to give to the user
     *
     */
    public function setUserPrivileges($adminUserId, $adminPassword, $userId, $privArray)
    {
        return $this->handler->setUserPrivileges($adminUserId, $adminPassword, $userId, $privArray);
    }
     
      /**
     * Gets application specific attributes from A&A
     *
     * @author Brad Shutters
     * @access public
     * @param string $adminUserId Admin making the request
     * @param string $adminPassword Admin's password
     * @param string $userId User to get attributes for
     * @return array Array of custom attributes
     *
     */
     public function getCustomAttributes($adminUserId, $adminPassword, $userId)
     {
        return $this->handler->getCustomAttributes($adminUserId, $adminPassword, $userId);
     }
     
     /**
     * Lists all available privileges for a given application
     *
     * @access public
     * @author Brad Shutters
     * @param string $appId App to set privileges for
     * @param string $adminUserId Admin making the request
     * @param string $adminPassword Admin's password
     * @return AAPrivilegeInterface Complete list of privileges
     *
     */
     public function listAppPrivileges($adminUserId, $adminPassword, $providerId='')
     {
        return $this->handler->listAppPrivileges($adminUserId, $adminPassword, $providerId);
     }
    

    
    /**
     * Get login URL
     *
     * Returns the Enterprise A&A SSO Login url that can be used by calling applications to
     * redirect users to the SSO login page when needed
     *
     * @author Tony Bibbbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $returnUrl URL to send the users to once they successfully login
     * @return string The URL to the A&A SSO login page
     *
     */
    public function getSignOnUrl($returnUrl,$headerLogo = null)
    {
        if ($this->options['useSSL']) {
            $proto = 'https';
        } else {
            $proto = 'http';
        }
        return sprintf('%s://%s%ssso?appId=%s&callingApp=%s&logo=%s', $proto, $this->options['aaServer'],
            $this->options['aaPath'],$this->options['appId'], urlencode($returnUrl),urlencode($headerLogo));
    }
    
    /**
     * Get signoff URL
     *
     * Returns the Enterprise A&A SSO logout URL that can be used by calling applications to kill
     * a SSO session
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public 
     * @param string $returnUrl URL to send the users to once they successfully logout
     * @return string The URL to the A&A SSO logout page
     *
     */
    function getSignOffUrl($returnUrl,$headerLogo = null)
    {
        if ($this->options['useSSL']) {
            $proto = 'https';
        } else {
            $proto = 'http';
        }
        return sprintf('%s://%s/%s/sso?commandName=Logoff&appId=%s&callingApp=%s&logo=%s', $proto, 
            $this->options['aaServer'], $this->options['aaPath'],$this->options['appId'], 
            urlencode($returnUrl),urlencode($headerLogo));        
    }

     /**
     * Allows an administrator to update a user account
     *
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @access public
     * @param string $tokenId Administrator's user token
     * @param string $userId User to update account for
     * @param string $password Password to authenticate with
     * @param string $fname First Name
     * @param string $lname Last Name
     * @param string $email Email Address
     * @param string $providerId Provider the user account exists in.  This is only needed for
     * those applications that support more than one provider
     *
     */
     public function updateAccountByToken($ssoToken, $userId, $password, $fname, $lname, $email, $phone='',$providerId = ''){
        
        return $this->handler->updateAccountByToken($ssoToken, $userId, $password, $fname, $lname, $email, $phone, $providerId);
        
     }    
    
    /**
    * Admin Change User Password (SSO)
    *
    * @author Justin Carlson <justin.carlson@iowa.gov>
    * @access public
    * @param string $ssoToken, $userId, $newPassword
    * @return void
    */
    public function changePasswordByToken($ssoToken, $userId, $newPassword, $providerId='')
    {
        return $this->handler->changePasswordByToken($ssoToken, $userId, $newPassword, $providerId);    
    }

    /** INCOMPLETE, UNTESTED
     * Gets application priveliges for specified user by token ( SSO )
     *
     * This method retrieves the privilges of the specified user
     *
     * @access public
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @param string $ssoToken
     * @param string $userId User to get privileges for
     * @return AAPrivilegeInterface Object holding the user's privileges
     *
     */
     public function getUserPrivilegesByToken($ssoToken, $userId, $providerId='')
    {
        return $this->handler->getUserPrivilegesByToken($ssoToken, $userId, $providerId);   
    }

     /**  INCOMPLETE, UNTESTED
     * Gets application specific attributes from A&A
     *
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @access public
     * @param string $ssoToken - SSO Token 
     * @param string $userId User to get attributes for
     * @return array Array of custom attributes
     *
     */
     public function getCustomAttributesByToken($ssoToken, $userId, $providerId='')
    {
        return $this->handler->getCustomAttributesByToken($ssoToken, $userId, $providerId); 
    }

     /**
     * Lists all available privileges for a given application
     *
     * @access public
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @param string $ssoToken - SSO Token
     * @return AAPrivilegeInterface Complete list of privileges
     *
     */
     public function listAppPrivilegesByToken($ssoToken, $providerId='')
    {
        return $this->handler->listAppPrivilegesByToken($ssoToken, $providerId);    
    }

    /**
     * Resets a password by Token (SSO)
     *
     * This method allows an admin user to reset the password for the specified user.
     * Returns the new, randomly generated password
     *
     * @access public
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @param string $ssoToken - SSO Token
     * @param string $userId User to reset password for
     * @return string Radomly generated password
     *
     */
    public function resetPasswordByToken($ssoToken, $userId, $providerId='')
    {
        return $this->handler->resetPasswordByToken($ssoToken, $userId, $providerId);   
    }

     /**
     * Sets the privileges for a given user and application
     *
     * @access public
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @param string $ssoToken - SSO Token
     * @param string $userId User to set privileges for
     * @param AAPrivilege[] $privileges Privileges to give to the user
     *
     */
    public function setUserPrivilegesByToken($ssoToken, $userId, $privArray='', $providerId='')
    {
        if (empty($privArray)) {
            $privArray = array();
        }
        return $this->handler->setUserPrivilegesByToken($ssoToken, $userId, $privArray, $providerId);   
    }
    
    /**
     * Creates a new Enterprise A&A account in the repository tied to the application
     *
     * NOTE: this method does not work yet on the Active Directory provider.
     *
     * @access public
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @param string $ssoToken - SSO Token
     * @param string $userId ID to assign to the new user
     * @param string $password Initial password for the user
     * @param string $fname User's first name
     * @param string $lname User's last name
     * @param string $email User's email address
     * @param array $privArray Array of privileges to assign to the user
     *
     */
    public function createAccountByToken($ssoToken,$userId, $password, $fname, $lname, $email='', $privArray='', $providerId='')
    {
        if (empty($privArray)) {
            $privArray = array();
        }
        return $this->handler->createAccountByToken($ssoToken,$userId, $password, $fname, $lname, $email, $privArray, $providerId);
    }
    
}

?>