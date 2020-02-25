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
 * @version $Id: User.php,v 1.3 2006/05/01 21:58:09 bshutters Exp $
 *
 */

/**
 * This is the A&A user object.  This object is responsible for handling all authentication and 
 * authorization functions for a user.  If you are using this service in an application you would 
 * be wise to extend this functionality.  This object was kept as small as possible to allow it to 
 * be put into a session.
 *
 * @access public
 * @author Tony Bibbs <tony.bibbs@iowa.gov>
 * @package gov.iowa.das.ite.aaclient
 * @todo Added getCustomAttributeValue which takes a string which is an index into _attributes and 
 * returns the corresponding value
 *
 */
class ITE_AAClient_User 
{
    
    /**
     * User ID
     * @access private
     * @var string
     */
    private $userId = null;
    
    /**
     * User's password
     * @access private
     * @var string
     */
    private $password = null;

    /**
     * First Name
     * @access private
     * @var string
     */
    private $firstName = null;
    
    /**
     * Last Name
     * @access private
     * @var string
     */    
    private $lastName = null;
    
    /**
     * Email
     * @access private
     * @var string
    */    
    private $email = null;
    
    /**
     * Employee Id
     * @access private
     * @var string
     */
    private $empId = null; 
    
    /**
     * Phone Number
     * @access private
     * @var string
     */    
    private $phoneNumber = null;
    
    /**
     * ID for the provider tied to the user. If this is blank, the service will use the default
     * provider
     * @access private
     * @var string
     */
    private $providerId = null;
    
    /**
     * Privileges user has
     * @access private
     * @var array
     */
    private $privileges = array();
        
    /**
     * A&A User attributes
     * @access private
     * @var array
     */
    private $attributes = array();   
    
    /**
     * Holds the appriopriate handler that will do the actual communication with A&A
     * @var ITE_AAClient_XMLHandler
     * @access private
     */
    private $handler = null;
     
    /**
     * Sets the user ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $userId User ID
     * 
     */
    public function getUserData()
    {
        return array(
            'user_id'       => $this->getUserId(),
            'password'      => $this->getPassword(),
            'first_name'    => $this->getFirstName(),
            'last_name'     => $this->getLastName(),
            'email'         => $this->getEmail(),
            'phone'         => $this->getPhoneNumber()
        );
    }
     
    /**
     * Sets the user ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $userId User ID
     * 
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    /**
     * Gets the user ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string user's ID
     * 
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * Sets user's password
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $password Password
     * 
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * Gets user's password
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string user's password
     * 
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Sets the first name
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string string User's first name
     * 
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    
    /**
     * Gets the first name
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string User's first name
     * 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * Sets the user's last name
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $lastName User's last name
     * 
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
    /**
     * Gets the user's last name
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string User's last name
     * 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * Sets the user's email address
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $email User's email address
     * 
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * Gets the user's email address
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string User's email address
     * 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Sets the user's phone number
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $phoneNumber User's phone number
     * 
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    /**
     * Gets the user's phone number
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string User's phone number
     * 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    /**
     * Sets the user's provider ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $providerId A&A Provider the user should be tied to
     * 
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;
    }
    
    /**
     * Gets the user's provider ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string A&A Provider the user object is tied to
     * 
     */
    public function getProviderId()
    {
        return $this->providerId;
    }
    
    /**
     * Sets the user's employee ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $empId User's Employee ID
     * 
     */
    public function setEmpId($empId)
    {
        $this->empId = $empId;
    }
    
    /**
     * Gets the user's employee ID
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return string User's employee ID
     * 
     */
    public function getEmpId()
    {
        return $this->empId;
    }
    
    /**
     * Sets the A&A Communication handler that we should use for communication with the A&A Service
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param ITE_AAClient_XMLHandler $handler Right now this is the only valid return value.  SOAP
     * support will be added in the future.
     * 
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }
    
    /**
     * Gets the A&A communication handler to use for all communication with the A&A Service
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return ITE_AAClient_XMLHandler Right now this is the only valid return value.  SOAP
     * support will be added in the future.
     * 
     */
    public function getHandler()
    {   
        return $this->handler;
    }
    
    /**
    * Returns the A&A user attributes
    *
    * @author Tony Bibbs <tony.bibbs@iowa.gov>
    * @access public
    * @return array Array of attributes we got from A&A
    *
    */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * Assigns a set of privileges to a user
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param array $privileges Array of privileges the user is to be given
     * 
     */
    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }
    
    /**
     * This method returns the list of all allowed privileges for this user
     * for the current application. Note this just gets what we already have.
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @return ITE_AAClient_Privilege[] Array of Privilege objects for this user
     *
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }
    
    /**
     * Determines if the user has the given privilege
     *
     * @author Tony Bibbs <tony.bibbs@iowa.gov>
     * @access public
     * @param string $privilegeName Privilege to check the user against
     * @return boolean True if user has the privilege, otherwise false
     *
     */
    public function hasPrivilege($privilegeName)
    {
        return in_array($privilegeName, $this->getPrivileges());
    }
}

?>