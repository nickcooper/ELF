<html>
 <head>
  <title>Unit Test For PHP5 Ent A&amp;A Client</title>
 </head>
 <body>
 <?php

    require_once 'PHPUnit2/Framework/TestCase.php';
    require_once 'Client.php';
    require_once 'PEAR/Exception.php';

    //constants used in testing
    define('APP_ID_VALID','ABD_ELIC');
    define('APP_ID_INVALID', 'XXYYZER');
    define('AA_TEST_SERVER', 'test.iowa.gov');
    define('USER_ID_VALID_2','A&A.Test1@Iowa.gov');
    define('PASSWORD_VALID_2','Iowa2005');
    define('USER_ID_VALID','Test47-0245@IowaID');
    define('PASSWORD_VALID','hrpn11!!');
    define('USER_ID_INVALID','TestUser@IowaID');
    define('PASSWORD_INVALID','bogus');
    define('USER_ID_INVALID_2','TestUser@IowaID');
    define('USER_ID_EMPTY','');
    define('PASSWORD_EMPTY','');
    define('TEST_ADMIN_USER','abd_elic@iowa.gov');
    define('TEST_ADMIN_PWD','Iowa7913');
    define('TEST_ADMIN_USER_INVALID','not_an_admin@testing.iowa.gov');
    define('PRIVILEGE_VALID','Licensee');
    define('PRIVILEGE_INVALID','Superman');
    define('PRIVILEGE_INVALID_COMPLEX','$00P3r/\/\@Nn!~');
    define('PROVIDER_ID_INVALID','INVALID');
    define('PROVIDER_ID_INVALID_COMPLEX','~N\/@LI|)<%>');

    class ITE_AAClient_ClientTest extends PHPUnit2_Framework_TestCase
    {
        private $createdUserId = null;
        private $createdUserPassword = null;

        public function testGetPasswordRulesWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $rules = $client->getPasswordRules();
            self::assertNotNull($rules);
            self::assertTrue(strlen(trim($rules))>0);
        }

        //test authenticate insufficient data
        public function testAuthenticateInsufficientData()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $userId = USER_ID_INVALID;
            $password = PASSWORD_EMPTY;

            try
            {
                $user = $client->authenticate($userId, $password);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INSUFFICIENT_DATA_EXCEPTION);
            }
        }

        public function testAuthenticateWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $userId = USER_ID_VALID;
            $password = PASSWORD_VALID;
            $user = $client->authenticate($userId, $password);
            self::assertNotNull($user);
            self::assertEquals($user->getUserId(),USER_ID_VALID);
        }

        public function testCabaFailsInvalidPriv()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);

            $userId = sprintf("Test%d-%d", (rand()%999), (rand()%999));
            $password = "Soup233!";

            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;

            $privs = array();
            $privs[0] = PRIVILEGE_INVALID;

            try
            {
                $client->createAccountViaAppUser($appUserId, $appUserPassword, $userId, $password, 'Unit Test', 'Account', 'noemail@unittest.iowa.gov', $privs);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_PRIVILEGE_EXCEPTION);
            }
        }

        //[Test, ExpectedException(typeof(exception.InvalidNewPasswordException))]
        public function testCabaFailsInvalidPassword()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $userId = sprintf("Test%d-%d", (rand()%999), (rand()%999));
            $password = PASSWORD_INVALID;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_VALID;

            try
            {
                $client->createAccountViaAppUser($appUserId, $appUserPassword, $userId, $password, 'Unit Test', 'Account', 'noemail@unittest.iowa.gov', $privs);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_NEW_PASSWORD_EXCEPTION);
            }
        }

        public function testNewAcountRequiresBaseline()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $password = $this->createdUserPassword;

            try
            {
                $client->authenticate($userId, $password);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), PASSWORD_EXPIRED_EXCEPTION);
            }
        }

        public function testChangePasswordOnNewAccount()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $oldPassword = $this->createdUserPassword;
            $newPassword = urlencode("NewP233%");
            $client->changePassword($userId, $oldPassword, $newPassword);
        }

        public function testChangePasswordByAdminWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $newPassword = "AnotherP233!";
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $client->changePasswordByAdmin($appUserId, $appUserPassword, $userId, $newPassword);
        }

        public function testCpbaAfterCabaFails()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $newPassword = "AnotherP233!";
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $client->changePasswordByAdmin($appUserId, $appUserPassword, $userId, $newPassword);
        }

        public function testCpbaInvalidPassword()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $newPassword = PASSWORD_INVALID;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;

            try
            {
                $client->changePasswordByAdmin($appUserId, $appUserPassword, $userId, $newPassword);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_NEW_PASSWORD_EXCEPTION);
            }
        }

        public function testSetUserPrivilegeInvalid()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_INVALID;

            try
            {
                $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $privs);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_PRIVILEGE_EXCEPTION);
            }
        }

        public function testSetUserPrivilegeInvalidComplex()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_INVALID_COMPLEX;

            try
            {
                $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $privs);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_PRIVILEGE_EXCEPTION);
            }
        }

        public function testSetUserPrivilegeValid()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_VALID;
            $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $privs);
        }

        public function testSetUserPrivilegeInvalidUser()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = sprintf("%s%s%s", "NOT_", $this->createdUserId, "!!!!");
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_VALID;

            try
            {
                $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $privs);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_USER_EXCEPTION);
            }
        }

        public function testSetUserPrivilegeInvalidAdmin()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER_INVALID;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_VALID;

            try
            {
                $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $privs);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_USER_EXCEPTION);
            }
        }

        public function testSetUserPrivilegeAllWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $this->listAppPrivileges());
        }

        public function testSetUserPrivilegeNoneWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $emptyarr = array();
            $client->setUserPrivileges($appUserId, $appUserPassword, $userId, $emptyarr);
        }

        public function testGetUserPrivilegeWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = $client->getUserPrivileges($appUserId, $appUserPassword, $userId);
            self::assertNotNull($privs);
        }

        public function testGetUserPrivilegeInvalidUser()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = sprintf("%s%s", $this->createdUserId, "INVALID!");
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;

            try
            {
                $client->getUserPrivileges($appUserId, $appUserPassword, $userId);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_USER_EXCEPTION);
            }
        }

        public function testGetUserPrivilegeInvalidAdmin()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER_INVALID;
            $appUserPassword = TEST_ADMIN_PWD;

            try
            {
                $client->getUserPrivileges($appUserId, $appUserPassword, $userId);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_USER_EXCEPTION);
            }
        }


        public function testListAppPrivilegesWorks()
        {
            self::assertNotNull($this->listAppPrivileges());
        }

        public function testListAppPrivilegesInvalidAdmin()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $appUserId = TEST_ADMIN_USER_INVALID;
            $appUserPassword = TEST_ADMIN_PWD;

            try
            {
                $client->listAppPrivileges($appUserId, $appUserPassword);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_USER_EXCEPTION);
            }
        }

        public function testListAppPrivilegesInvalidProvider()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $provId = PROVIDER_ID_INVALID;

            try
            {
                $client->listAppPrivileges($appUserId, $appUserPassword, $provId);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), UNEXPECTED_FATAL_EXCEPTION);
            }
        }

        public function testListAppPrivilegesInvalidProviderComplex()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $provId = PROVIDER_ID_INVALID_COMPLEX;

            try
            {
                $client->listAppPrivileges($appUserId, $appUserPassword, $provId);
            }
            catch(PEAR_Exception $e)
            {
                echo $e->getCode();
                self::assertEquals($e->getCode(), UNEXPECTED_FATAL_EXCEPTION);
            }
        }

        public function testResetPasswordWorks()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $newPassword = $client->resetPassword($appUserId, $appUserPassword, $userId);
            self::assertNotNull($newPassword);
        }

        public function testResetPasswordInvalidAdmin()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $this->createRandomAccount();
            $userId = $this->createdUserId;
            $appUserId = TEST_ADMIN_USER_INVALID;
            $appUserPassword = TEST_ADMIN_PWD;

            try
            {
                $client->resetPassword($appUserId, $appUserPassword, $userId);
            }
            catch(PEAR_Exception $e)
            {
                self::assertEquals($e->getCode(), INVALID_USER_EXCEPTION);
            }
        }

        private function listAppPrivileges()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            return $client->listAppPrivileges($appUserId, $appUserPassword);
        }

        public function createRandomAccount()
        {
            $client = new ITE_AAClient_Client('XML', APP_ID_VALID, AA_TEST_SERVER);
            $userId = sprintf("Test%d-%d", (rand()%999), (rand()%999));
            $password = 'tmp5pass!';
            $appUserId = TEST_ADMIN_USER;
            $appUserPassword = TEST_ADMIN_PWD;
            $privs = array();
            $privs[0] = PRIVILEGE_VALID;
            $client->createAccountViaAppUser($appUserId, $appUserPassword, $userId, $password, 'Unit Test', 'Account', 'noemail@unittest.iowa.gov', $privs);
            $this->createdUserId = $userId;
            $this->createdUserPassword = $password;
        }


    }



 ?>
 </body>
</html>