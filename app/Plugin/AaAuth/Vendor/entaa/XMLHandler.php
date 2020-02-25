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
 * @version $Id: XMLHandler.php,v 1.18 2007/01/16 21:04:11 justincarlson Exp $
 *
 */

/**
 * The A&A service client interface
 */
require_once 'ServiceInterface.php';

/**
 * The A&A service result code constants
 */
require_once 'Constants.php';
/**
 * This file defines the application program interface into ITE's Enterprise A&A Service
 *
 * This class implements communication to the A&A Serivce using raw XML posted over HTTP or HTTPS.
 * The XML for all requests is in the general format of:
 *
 * <AAService>
 *     <someMethodToCall>
 *         <argument1/>
 *         <argument2/>
 *         <argumentN/>
 *     </someMethodToCall>
 * </AAService>
 *
 * Similarly, all responses to the service take the general format of:
 *
 * <AAService>
 *     <Result value="" method="" appId=""></Result>
 *     <returnVal1/>
 *     <returnVal2/>
 *     <returnVal3/>
 * </AAService>
 *
 * For a complete description of the XML required for each method see the ITE document called
 * XML_Definitions.doc.
 *
 * @package gov.iowa.das.ite.aaclient
 *
 */
class ITE_AAClient_XMLHandler implements ITE_AAClient_ServiceInterface  {

    /**
     * Application ID assigned to the application
     * @var string
     * @access private
     */
    private $appId = null;

    /**
     * Host name for the A&A Server to talk to
     * @var string
     * @access private
     */
    private $aaServer = null;

    /**
     * Port to talk to the A&A Server over
     * @var int
     * @access private
     */
    private $aaPort = null;

    /**
     * Indicates whether or not to use SSL while talking to the A&A Service
     * @var boolean
     * @access private
     */
    private $useSSL = null;

    /**
     * Path on the server to send requests to
     * @var string
     * @access private
     */
    private $aaPath = null;

    /**
     * Holds instance of the native PHP5 XML DOM
     * @var DomDocument
     * @access private
     */
    private $dom = null;

    /**
     * Constructor
     *
     * Prepares for communication to the A&A Service
     *
     * NOTE: if your application runs on a server outside the proxy, you will need to talk to
     * A&A via SSL.  If you are on a server behind the ITE proxy, then you cannot use port 80
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $aaServer Host name of the A&A Server
     * @param int $aaPort Port to talk over on A&A Server
     * @param boolean $useSSL Whether or not we should talk over SSL to the A&A Service
     *
     */
    public function __construct($appId, $aaServer, $aaPath='/entaa', $useSSL=true, $aaPort='')
    {
        // Set needed configuration options for A&A.
        $this->appId = $appId;
        $this->aaServer = $aaServer;
        $this->aaPort = $aaPort;
        $this->useSSL = $useSSL;
        $this->aaPath = $aaPath;
        
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
        
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('GetPasswordRules');
        // Application ID node
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);
        // Provider Id node
        $tmpNode = $this->dom->createElement('ProviderId');
        $tmpNode->setAttribute('value', $providerId);
        $methodNode->appendChild($tmpNode);
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
        //get randomly generated password and return it
        $xpath = new DOMXPath($this->dom);
        $tmpList = $xpath->query('/AAService/PasswordRules');
        $therules = $tmpList->item(0)->getAttribute('value');
        return $therules;
    }
    /**
     * Authenticates a user
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $userId User to authenticate
     * @param string $password Password to authenticate user with
     * @param string $providerId ID of provider to use
     *
     */
    public function &authenticate($userId, $password, $providerId = '')
    {
        try
        {
            // reset the DomDoc so we can re-use the same client over and over
            $this->dom = new DomDocument();
        
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('Authenticate');
            // Application ID node
            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value',$this->appId);
            $methodNode->appendChild($tmpNode);
    
            // User ID node
            $tmpNode = $this->dom->createElement('UserId');
            
            $tmpNode->setAttribute('value',''.$userId.'');
            $methodNode->appendChild($tmpNode);
    
            // Password node
            $tmpNode = $this->dom->createElement('Password');
            $tmpNode->setAttribute('value', $password);
            $methodNode->appendChild($tmpNode);

            //commenting this out because even if there is no provider id,
            //the .net client still passess it with an empty value
            //if (!empty($providerId)) {
                // Provider ID node.  This is only used when an application supports authenticating to
                // more than one provider.  For most applications this will not be provided
                $tmpNode = $this->dom->createElement('ProviderId');
                $tmpNode->setAttribute('value', $providerId);
                $methodNode->appendChild($tmpNode);
            //}
    
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();
            $userObj = $this->getUserFromResponse();
            $userObj->setHandler($this);
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $userObj;
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('CreateAccount');

        // Application ID node
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);

        // User ID node
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value',$userId);
        $methodNode->appendChild($tmpNode);

        // Password node
        $tmpNode = $this->dom->createElement('Password');
        $tmpNode->setAttribute('value', $password);
        $methodNode->appendChild($tmpNode);

        // First Name node
        $tmpNode = $this->dom->createElement('FirstName');
        $tmpNode->setAttribute('value', $fname);
        $methodNode->appendChild($tmpNode);

        // Last Name node
        $tmpNode = $this->dom->createElement('LastName');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);

        // Email node
        $tmpNode = $this->dom->createElement('Email');
        $tmpNode->setAttribute('value', $email);
        $methodNode->appendChild($tmpNode);

        // Priv node
        $tmpNode = $this->dom->createElement('PrivilegeList');

        foreach($privArray as $priv)
        {
            $privNode = $this->dom->createElement('Privilege');
            $privNode->setAttribute('value', $priv);
            $tmpNode->appendChild($privNode);
        }
        $methodNode->appendChild($tmpNode);

        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);

        $this->makeRequest();

        $userObj = $this->getUserFromResponse();

        $userObj->setHandler($this);

        return $userObj;

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
    public function createAccountViaAppUser($applicationUser, $applicationPass, $userId, $password, $fname, $lname, $email='', $privArray='')
    {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('CreateAccountByAdmin');

        // Application ID node
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);

        // Application ID node
        $tmpNode = $this->dom->createElement('ApplicationUserId');
        $tmpNode->setAttribute('value',$applicationUser);
        $methodNode->appendChild($tmpNode);

        // Application ID node
        $tmpNode = $this->dom->createElement('ApplicationPassword');
        $tmpNode->setAttribute('value',$applicationPass);
        $methodNode->appendChild($tmpNode);

        // User ID node
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value',$userId);
        $methodNode->appendChild($tmpNode);

        // Password node
        $tmpNode = $this->dom->createElement('Password');
        $tmpNode->setAttribute('value', $password);
        $methodNode->appendChild($tmpNode);
        // First Name node
        $tmpNode = $this->dom->createElement('FirstName');
        $tmpNode->setAttribute('value', $fname);
        $methodNode->appendChild($tmpNode);
        // Last Name node
        $tmpNode = $this->dom->createElement('LastName');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);
        // Email node
        $tmpNode = $this->dom->createElement('Email');
        $tmpNode->setAttribute('value', $email);
        $methodNode->appendChild($tmpNode);

        // Priv node
        $tmpNode = $this->dom->createElement('PrivilegeList');
        foreach($privArray as $priv)
        {
            $privNode = $this->dom->createElement('Privilege');
            $privNode->setAttribute('value', $priv);
            $tmpNode->appendChild($privNode);
        }
        $methodNode->appendChild($tmpNode);
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
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

    public function changePassword($userId, $oldPassword, $newPassword)
    {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('Authenticate');
        $appNode = $this->dom->createElement($this->appId);
        $methodNode->appendChild($appNode);
        $methodNode = $this->dom->createElement('ChangePassword');

        // Application ID node
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);

        // User ID node
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);

        // Old Password node
        $tmpNode = $this->dom->createElement('OldPassword');
        $tmpNode->setAttribute('value', $oldPassword);
        $methodNode->appendChild($tmpNode);

        // New Password node
        $tmpNode = $this->dom->createElement('NewPassword');
        $tmpNode->setAttribute('value', $newPassword);
        $methodNode->appendChild($tmpNode);

        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);

        $this->makeRequest();

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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('UpdateAccount');
        // set app id
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        // set user id
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);
        // set password
        $tmpNode = $this->dom->createElement('Password');
        $tmpNode->setAttribute('value', $password);
        $methodNode->appendChild($tmpNode);
        // First Name node
        $tmpNode = $this->dom->createElement('FirstName');
        $tmpNode->setAttribute('value', $fname);
        $methodNode->appendChild($tmpNode);
        // Last Name node
        $tmpNode = $this->dom->createElement('LastName');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);
        // Email node
        $tmpNode = $this->dom->createElement('Email');
        $tmpNode->setAttribute('value', $email);
        $methodNode->appendChild($tmpNode);
        // Provider ID node.  This is only used when an application supports authenticating to
        // more than one provider.  For most applications this will not be provided
        if (!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('UpdateAccountByAdmin');
        // set app id
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        // set admin user id
        $tmpNode = $this->dom->createElement('AdminUserId');
        $tmpNode->setAttribute('value', $adminUserId);
        $methodNode->appendChild($tmpNode);
        // set admin password
        $tmpNode = $this->dom->createElement('AdminPassword');
        $tmpNode->setAttribute('value', $adminPassword);
        $methodNode->appendChild($tmpNode);
        // set user id
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);
        // set new password
        $tmpNode = $this->dom->createElement('Password');
        $tmpNode->setAttribute('value', $password);
        $methodNode->appendChild($tmpNode);
        // First Name node
        $tmpNode = $this->dom->createElement('FirstName');
        $tmpNode->setAttribute('value', $fname);
        $methodNode->appendChild($tmpNode);
        // Last Name node
        $tmpNode = $this->dom->createElement('LastName');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);
        // Email node
        $tmpNode = $this->dom->createElement('Email');
        $tmpNode->setAttribute('value', $email);
        $methodNode->appendChild($tmpNode);
        // Provider ID node.  This is only used when an application supports authenticating to
        // more than one provider.  For most applications this will not be provided
        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('ChangePasswordByAdmin');
        // set app id
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        // set admin user id
        $tmpNode = $this->dom->createElement('AdminUserId');
        $tmpNode->setAttribute('value', $adminUserId);
        $methodNode->appendChild($tmpNode);
        // set admin password
        $tmpNode = $this->dom->createElement('AdminPassword');
        $tmpNode->setAttribute('value', $adminPassword);
        $methodNode->appendChild($tmpNode);
        // set user id
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);
        // set new password
        $tmpNode = $this->dom->createElement('NewPassword');
        $tmpNode->setAttribute('value', $newPassword);
        $methodNode->appendChild($tmpNode);
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
    }
    
    /**
     * Resets a password
     *
     * This method allows an admin user to reset the password for the specified user.
     * Returns the new, randomly generated password
     *
     * @access public
     * @author Justin Carlson <justin.carlson@iowa.gov>
     * @param string $adminUserId Admin performing the reset
     * @param string $adminPassword Admin's password
     * @param string $userId User to reset password for
     * @return string Radomly generated password
     *
     */
    public function resetPassword($adminUserId, $adminPassword, $userId)
    {
        
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
        
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('ResetPassword');
        // set app id
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        // set admin user id
        $tmpNode = $this->dom->createElement('AdminUserId');
        $tmpNode->setAttribute('value', $adminUserId);
        $methodNode->appendChild($tmpNode);
        // set admin password
        $tmpNode = $this->dom->createElement('AdminPassword');
        $tmpNode->setAttribute('value', $adminPassword);
        $methodNode->appendChild($tmpNode);
        // set user id
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
        //get randomly generated password and return it
        $xpath = new DOMXPath($this->dom);
        $tmpList = $xpath->query('/AAService/NewPassword');
        $newPassword = $tmpList->item(0)->getAttributeNode('value')->value;   
        return $newPassword;
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('GetUserPrivileges');
            // set app id
            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value', $this->appId);
            $methodNode->appendChild($tmpNode);
            // set admin user id
            $tmpNode = $this->dom->createElement('AdminUserId');
            $tmpNode->setAttribute('value', $adminUserId);
            $methodNode->appendChild($tmpNode);
            // set admin password
            $tmpNode = $this->dom->createElement('AdminPassword');
            $tmpNode->setAttribute('value', $adminPassword);
            $methodNode->appendChild($tmpNode);
            // set user id
            $tmpNode = $this->dom->createElement('UserId');
            $tmpNode->setAttribute('value',$userId);
            $methodNode->appendChild($tmpNode);
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();
            //get privileges from response and return them
            $xpath = new DOMXPath($this->dom);
            $tmpList = $xpath->query('/AAService/PrivilegeList/Privilege');
            return $this->xmlPrivilegesToObjects($tmpList);
     }
     /**
     * Method to Creat A Tag List with Values
     * Appends the Tag and Value pairs to a node and returns that node
     *
     * @author Brad Shutters
     * @access private
     * @param listTagname Name of the list element
     * @param tagName Name of tag to append
     * @param values Value for the attribute specified by the tag name
     * @return DOM representation
     */
    private function CreateListOfTagsWithValue($listTagname, $tagname, $values)
    {
        $listNode = $this->dom->createElement($listTagname);
        foreach ($values as $value)
        {
            $tmpNode = $this->dom->createElement($tagname);
            $tmpNode->setAttribute('value', $value);
            $listNode->appendChild($tmpNode);
        }
        return $listNode;
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('SetUserPrivileges');
            // set app id
            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value', $this->appId);
            $methodNode->appendChild($tmpNode);
            // set admin user id
            $tmpNode = $this->dom->createElement('AdminUserId');
            $tmpNode->setAttribute('value', $adminUserId);
            $methodNode->appendChild($tmpNode);
            // set admin password
            $tmpNode = $this->dom->createElement('AdminPassword');
            $tmpNode->setAttribute('value', $adminPassword);
            $methodNode->appendChild($tmpNode);
            // set user id
            $tmpNode = $this->dom->createElement('UserId');
            $tmpNode->setAttribute('value', $userId);
            $methodNode->appendChild($tmpNode);
            //attach privileges
            $privsNode = $this->CreateListOfTagsWithValue('PrivilegeList', 'Privilege', $privArray);
            $methodNode->appendChild($privsNode);
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('GetCustomAttributes');
            // set app id
            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value', $this->appId);
            $methodNode->appendChild($tmpNode);
            // set admin user id
            $tmpNode = $this->dom->createElement('AdminUserId');
            $tmpNode->setAttribute('value', $adminUserId);
            $methodNode->appendChild($tmpNode);
            // set admin password
            $tmpNode = $this->dom->createElement('AdminPassword');
            $tmpNode->setAttribute('value', $adminPassword);
            $methodNode->appendChild($tmpNode);
            // set user id
            $tmpNode = $this->dom->createElement('UserId');
            $tmpNode->setAttribute('value', $userId);
            $methodNode->appendChild($tmpNode);
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();
            //now return the custom attributes
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('ListAppPrivileges');
        // set app id
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        // set admin user id
        $tmpNode = $this->dom->createElement('AdminUserId');
        $tmpNode->setAttribute('value', $adminUserId);
        $methodNode->appendChild($tmpNode);
        // set admin password
        $tmpNode = $this->dom->createElement('AdminPassword');
        $tmpNode->setAttribute('value', $adminPassword);
        $methodNode->appendChild($tmpNode);
        // Provider ID node.  This is only used when an application supports authenticating to
        // more than one provider.  For most applications this will not be provided
        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
        //get privileges from response and return them
        $xpath = new DOMXPath($this->dom);
        $tmpList = $xpath->query('/AAService/PrivilegeList/Privilege');
        return $this->xmlPrivilegesToObjects($tmpList);
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('GetUserObject');
        //$appNode = $this->dom->createElement($this->appId);
        //$methodNode->appendChild($appNode);

        // Application ID node
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);
        // Token ID node
        $tmpNode = $this->dom->createElement('TokenId');
        $tmpNode->setAttribute('value',$ssoToken);
        $methodNode->appendChild($tmpNode);
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
        $userObj = $this->getUserFromResponse();
        $userObj->setHandler($this);
        return $userObj;
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
     public function updateAccountByToken($ssoToken, $userId, $password, $fname, $lname, $email,$phone='',$providerId = '')
     {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('UpdateUserAccountByToken');
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('TokenId');
        $tmpNode->setAttribute('value',$ssoToken);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('Password');
        $tmpNode->setAttribute('value', $password);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('FirstName');
        $tmpNode->setAttribute('value', $fname);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('LastName');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('PhoneNumber');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);
        $tmpNode = $this->dom->createElement('Email');
        $tmpNode->setAttribute('value', $email);
        $methodNode->appendChild($tmpNode);
        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
     }

     
    /**
    * Admin Change User Password (SSO)
    *
    * @author Justin Carlson <justin.carlson@iowa.gov>
    * @access public
    * @param string $ssoToken, $userId, $newPassword
    * @return void
    */
    public function changePasswordByToken($ssoToken, $userId, $newPassword,$providerId='')
    {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('ChangePasswordByAdminToken');
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);

        $tmpNode = $this->dom->createElement('TokenId');
        $tmpNode->setAttribute('value',$ssoToken);
        $methodNode->appendChild($tmpNode);
        
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);
        
        $tmpNode = $this->dom->createElement('NewPassword');
        $tmpNode->setAttribute('value', $newPassword);
        $methodNode->appendChild($tmpNode);

        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
                
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();   
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
     public function getUserPrivilegesByToken($ssoToken, $userId,$providerId='')
     {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('GetUserPrivilegesByToken');

            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value', $this->appId);
            $methodNode->appendChild($tmpNode);

            $tmpNode = $this->dom->createElement('TokenId');
            $tmpNode->setAttribute('value',$ssoToken);
            $methodNode->appendChild($tmpNode);
                        
            $tmpNode = $this->dom->createElement('UserId');
            $tmpNode->setAttribute('value',$userId);
            $methodNode->appendChild($tmpNode);

            if(!empty($providerId))
            {
                $tmpNode = $this->dom->createElement('ProviderId');
                $tmpNode->setAttribute('value', $providerId);
                $methodNode->appendChild($tmpNode);
            }
                    
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();

            $xpath = new DOMXPath($this->dom);
            $tmpList = $xpath->query('/AAService/PrivilegeList/Privilege');
            return $this->xmlPrivilegesToObjects($tmpList);
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
     public function getCustomAttributesByToken($ssoToken, $userId,$providerId='')
     {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('GetCustomAttributesByToken');

            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value', $this->appId);
            $methodNode->appendChild($tmpNode);

            $tmpNode = $this->dom->createElement('TokenId');
            $tmpNode->setAttribute('value',$ssoToken);
            $methodNode->appendChild($tmpNode);

            $tmpNode = $this->dom->createElement('UserId');
            $tmpNode->setAttribute('value', $userId);
            $methodNode->appendChild($tmpNode);
            
            if(!empty($providerId))
            {
                $tmpNode = $this->dom->createElement('ProviderId');
                $tmpNode->setAttribute('value', $providerId);
                $methodNode->appendChild($tmpNode);
            }
                    
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();

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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('ListAppPrivilegesByToken');
        
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);

        $tmpNode = $this->dom->createElement('TokenId');
        $tmpNode->setAttribute('value',$ssoToken);
        $methodNode->appendChild($tmpNode);
        
        
        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
        
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
        
        $xpath = new DOMXPath($this->dom);
        $tmpList = $xpath->query('/AAService/PrivilegeList/Privilege');
        return $this->xmlPrivilegesToObjects($tmpList);
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
    public function resetPasswordByToken($ssoToken, $userId,$providerId='')
    {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('ResetPasswordByToken');
        
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value', $this->appId);
        $methodNode->appendChild($tmpNode);
        
        $tmpNode = $this->dom->createElement('TokenId');
        $tmpNode->setAttribute('value',$ssoToken);
        $methodNode->appendChild($tmpNode);

        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value', $userId);
        $methodNode->appendChild($tmpNode);

        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
                    
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);
        $this->makeRequest();
               
        $xpath = new DOMXPath($this->dom);
        $tmpList = $xpath->query('/AAService/NewPassword');
        $newPassword = $tmpList->item(0)->getAttributeNode('value')->value;
    
        return $newPassword;
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
    public function setUserPrivilegesByToken($ssoToken, $userId, $privArray='',$providerId='')
    {
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
            $serviceNode = $this->dom->createElement('AAService');
            $methodNode = $this->dom->createElement('SetUserPrivilegesByToken');
            
            $tmpNode = $this->dom->createElement('AppId');
            $tmpNode->setAttribute('value', $this->appId);
            $methodNode->appendChild($tmpNode);

            $tmpNode = $this->dom->createElement('TokenId');
            $tmpNode->setAttribute('value',$ssoToken);
            $methodNode->appendChild($tmpNode);
                    
            $tmpNode = $this->dom->createElement('UserId');
            $tmpNode->setAttribute('value', $userId);
            $methodNode->appendChild($tmpNode);

            $privsNode = $this->CreateListOfTagsWithValue('PrivilegeList', 'Privilege', $privArray);
            $methodNode->appendChild($privsNode);
            
            if(!empty($providerId))
            {
                $tmpNode = $this->dom->createElement('ProviderId');
                $tmpNode->setAttribute('value', $providerId);
                $methodNode->appendChild($tmpNode);
            }
            
            $serviceNode->appendChild($methodNode);
            $this->dom->appendChild($serviceNode);
            $this->makeRequest();
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
        // reset the DomDoc so we can re-use the same client over and over
        $this->dom = new DomDocument();
                
        $serviceNode = $this->dom->createElement('AAService');
        $methodNode = $this->dom->createElement('CreateAccountByAdminToken');

        // Application ID node
        $tmpNode = $this->dom->createElement('AppId');
        $tmpNode->setAttribute('value',$this->appId);
        $methodNode->appendChild($tmpNode);

        // SSO TOKEN
        $tmpNode = $this->dom->createElement('TokenId');
        $tmpNode->setAttribute('value',$ssoToken);
        $methodNode->appendChild($tmpNode);
                    
        // User ID node
        $tmpNode = $this->dom->createElement('UserId');
        $tmpNode->setAttribute('value',$userId);
        $methodNode->appendChild($tmpNode);

        // Password node
        $tmpNode = $this->dom->createElement('Password');
        $tmpNode->setAttribute('value', $password);
        $methodNode->appendChild($tmpNode);

        // First Name node
        $tmpNode = $this->dom->createElement('FirstName');
        $tmpNode->setAttribute('value', $fname);
        $methodNode->appendChild($tmpNode);

        // Last Name node
        $tmpNode = $this->dom->createElement('LastName');
        $tmpNode->setAttribute('value', $lname);
        $methodNode->appendChild($tmpNode);

        // Email node
        $tmpNode = $this->dom->createElement('Email');
        $tmpNode->setAttribute('value', $email);
        $methodNode->appendChild($tmpNode);

        // Priv node
        $tmpNode = $this->dom->createElement('PrivilegeList');

        foreach($privArray as $priv)
        {
            $privNode = $this->dom->createElement('Privilege');
            $privNode->setAttribute('value', $priv);
            $tmpNode->appendChild($privNode);
        }
        $methodNode->appendChild($tmpNode);

        if(!empty($providerId))
        {
            $tmpNode = $this->dom->createElement('ProviderId');
            $tmpNode->setAttribute('value', $providerId);
            $methodNode->appendChild($tmpNode);
        }
        $serviceNode->appendChild($methodNode);
        $this->dom->appendChild($serviceNode);

        $this->makeRequest();

        return null;

    }
        
    /**
     * Makes the acutal requests to the A&A Service
     *
     * Uses the cURL library to send the XML request to the A&A Service.  This method will also
     * check for any known errors we might get from the A&A service and will throw the appropriate
     * exceptions if one is encountered.
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access private
     *
     */
    private function makeRequest()
    {
        if (!function_exists('curl_init')) {
            echo '<h1 style="font-size:30pt;color:red;font-family:arial;font-weight:bold;">The cURL libs and php functions were not found.</h1>';
            echo '<b>The A&A libs require libcurl >= 7.12.0 and OpenSSL >= 0.9.7 31 Dec 2002</b><br/><br/>';
            return;
        }

        if ($this->useSSL) {
            $proto = 'https';
            if (empty($this->aaPort)) {
               $this->aaPort = 443;
            }
        } else {
            $proto = 'http';
            if (empty($this->aaPort)) {
               $this->aaPort = 80;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_PORT, $this->aaPort);
        curl_setopt($ch, CURLOPT_URL, sprintf('%s://%s%s', $proto, $this->aaServer, $this->aaPath));
        
        // This is need for windows OS only
        //if (stristr($_ENV['OS'], 'windows')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //}
        $xml = $this->dom->saveXML();
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, sprintf('xmlInParam=%s', urlencode($xml)));
        curl_setopt($ch, CURLOPT_CRLF, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $xmlResponse = curl_exec($ch);
        
        if (!$xmlResponse) {
            throw new Exception('cURL error encountered when communicating with the A&A service: '
                . curl_error($ch));
        }

        curl_close ($ch);

        // Since we are reading from the socket let's get rid of any header info
        $startPos = strpos($xmlResponse,'<');
        $endPos = strrpos($xmlResponse,'>') + 1;
        $length = ($endPos - $startPos) + 1;
        
        // Load the XML response into the DOM
        $this->dom->loadXML(substr($xmlResponse, $startPos, $length));
        try
        {
            $this->exceptionCheck($xmlResponse);
        }
        catch(Exception $e)
        {
            // Throw the error
            throw new PEAR_Exception($e->getMessage(), $e->getCode());
        }
        
        // Now we have just the XML
        return $xmlResponse;
    }

    /**
     * Checks for any errors we might get from the A&A Service.
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access private
     * @throws Exception
     *
     */
    private function exceptionCheck()
    {
        $xpath = new DOMXPath($this->dom);
        $resultList = $xpath->query('/AAService/Result');
        $tmpResult = $resultList->item(0);
        $retVal = $tmpResult->getAttribute('value');
        switch ($retVal) {
            case SUCCESSFUL_RETURN:
                return;
            case INVALID_USER_EXCEPTION:
                $msg = 'A&A Service: Invalid user ID';
                
                $eCode = INVALID_USER_EXCEPTION;
                break;
            case ACCOUNT_LOCKED_EXCEPTION:
                $msg = 'A&A Service: The user account is locked';
                $eCode = ACCOUNT_LOCKED_EXCEPTION;
                break;
            case CHANGE_PASSWORD_EXCEPTION:
                $msg = 'A&A Service: The user must change their password immediately';
                $eCode = CHANGE_PASSWORD_EXCEPTION;
                break;
            case INSUFFICIENT_DATA_EXCEPTION:
                $msg = 'A&A Service: The method called did not receive enough data to process the
                    request';
                $eCode = INSUFFICIENT_DATA_EXCEPTION;
                break;
            case INVALID_NEW_PASSWORD_EXCEPTION:
                $msg = 'A&A Service: The new password given did not meet the password rules';
                $eCode = INVALID_NEW_PASSWORD_EXCEPTION;
                break;
            case ADMIN_PERMISSION_EXCEPTION:
                $msg  = 'A&A Service: The user given doe not have sufficient permission to preform
                    the requested action';
                $eCode = ADMIN_PERMISSION_EXCEPTION;
                break;
            case ADMIN_ACCOUNT_LOCKED_EXCEPTION:
                $msg = 'A&A Service: The administrator suer account is locked';
                $eCode = ADMIN_ACCOUNT_LOCKED_EXCEPTION;
                break;
            case INVALID_PRIVILEGE_EXCEPTION:
                $msg = 'A&A Service: One of the privileges given is invalid';
                $eCode = INVALID_PRIVILEGE_EXCEPTION;
                break;
            case PASSWORD_EXPIRED_EXCEPTION:
                $msg = 'A&A Service: The password for the given user has expired';
                $eCode = PASSWORD_EXPIRED_EXCEPTION;
                break;
            case ACCOUNT_DISABLED_EXCEPTION:
                $msg = 'A&A Service: The account for the given user is disabled';
                $eCode = ACCOUNT_DISABLED_EXCEPTION;
                break;
            case ADMIN_ACCOUNT_DISABLED_EXCEPTION:
                $msg = 'A&A Service: The administrator account is disabled';
                $eCode = ADMIN_ACCOUNT_DISABLED_EXCEPTION;
                break;
            case ADMIN_ACCOUNT_NOT_INITIALIZED_EXCEPTION:
                $msg = 'A&A Service: The administrator account has not been intialized yet';
                $eCode = ADMIN_ACCOUNT_NOT_INITIALIZED_EXCEPTION;
                break;
            case INVALID_TOKEN_EXCEPTION:
                $msg = 'A&A Service: An invalid Single-Sign-on token was given';
                $eCode = INVALID_TOKEN_EXCEPTION;
                break;
            case UNEXPECTED_FATAL_EXCEPTION:
                $msg = 'A&A Service: The A&A Service received an unknown fatal error';
                $eCode = UNEXPECTED_FATAL_EXCEPTION;
                break;
            default:
                $msg = 'A&A Service: The A&A Service returned an unrecognized response';
                $eCode = -1;
        }

        // PEAR's Exception Class.  Bringing the code in only if needed.
        require_once 'PEAR/Exception.php';

        // Throw the error
        throw new PEAR_Exception($msg, $eCode);
    }

    /**
     * Converts an A&A response into a user object
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access private
     * @return ITE_AAClient_User
     *
     */
    private function getUserFromResponse()
    {
        require_once 'User.php';
        $userObj = new ITE_AAClient_User();
        $xpath = new DOMXPath($this->dom);
        $tmpList = $xpath->query('/AAService/UserId');
        $tmpItem = $tmpList->item(0);
        $userObj->setUserId($tmpItem->textContent);
        $tmpList = $xpath->query('/AAService/EmpId');
        $tmpItem = $tmpList->item(0);
        $userObj->setEmpId($tmpItem->textContent);
        $tmpList = $xpath->query('/AAService/ProviderId');
        $tmpItem = $tmpList->item(0);
        $userObj->setProviderId($tmpItem->textContent);
        $tmpList = $xpath->query('/AAService/FirstName');
        $tmpItem = $tmpList->item(0);
        $userObj->setFirstName($tmpItem->textContent);
        $tmpList = $xpath->query('/AAService/LastName');
        $tmpItem = $tmpList->item(0);
        $userObj->setLastName($tmpItem->textContent);
        $tmpList = $xpath->query('/AAService/Email');
        $tmpItem = $tmpList->item(0);
        $userObj->setEmail($tmpItem->textContent);
        $tmpList = $xpath->query('/AAService/PhoneNumber');
        $tmpItem = $tmpList->item(0);
        $userObj->setPhoneNumber($tmpItem->textContent);

        // Get privileges
        $tmpList = $xpath->query('/AAService/PrivilegeList/Privilege');
        $userObj->setPrivileges($this->xmlPrivilegesToObjects($tmpList));
        return $userObj;
    }

    /**
     * Converts a DOM representation of privilege objects and converts them into an array
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access private
     * @param NodeList $domPrivs DOM representation of privileges from an XML string
     * @return array Array of privileges the user has access to
     *
     */
    private function xmlPrivilegesToObjects($domPrivs)
    {
        $userPrivs = array();
        foreach ($domPrivs as $curPriv)
        {
            $userPrivs[] = $curPriv->getAttribute('code');
        }
        return $userPrivs;
    }
}

?>