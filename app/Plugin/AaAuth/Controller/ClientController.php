<?php
/**
 * ClientController
 *
 * @package AaAuth.Controller
 * @author  Iowa Interactive, LLC.
 */
class ClientController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Client';

    /**
     * Autoload models
     *
     * @var string
     * @access public
     */
    public $uses = array('Accounts.Account');

    /**
     * @var obj
     */
    private $_model_location = null;
    private $_model_classname = null;


    /**
     * beforeFilter method
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // load and init the user model
        $this->model_location = Configure::read('AaAuth.user.model');
        $this->model_classname = preg_replace('/^([^\.]+\.)+/', '', $this->model_location);

        $this->loadModel($this->model_location);

        // whitelist the public auth stuff
        $this->Auth->allow(array('login', 'logout', 'register'));
    }

    /**
     * beforeRender method
     *
     * @return void
     */
    public function beforeRender()
    {

        $this->set('reg_link', $this->_generateAALink(array('tab=createacct')));
        $this->set('login_link', $this->_generateAALink(array('tab=login')));
        $this->set('forgot_id_link', $this->_generateAALink(array('tab=forgotid')));
        $this->set('forgot_pass_link', $this->_generateAALink(array('tab=forgotpwd')));
        $this->set('aa_bypass', Configure::read('AaAuth.bypass.mode'));
    }

    /**
     * login method
     *
     * Auth handles AA login requests. However, this method remains
     * required in this controller.
     *
     * @return bool
     *
     * @todo Updated the last logged in
     */
    public function login()
    {
        // reset any previous sessions
        $this->Session->destroy();
        
        // check for AaAuth bypass setting in ii_core
        if ($username = Configure::read('AaAuth.bypass.mode'))
        {
            // re-check for existing local account
            $data = $this->{$this->model_classname}->findByUsername($username);

            // authenticate the local account
            if (! $this->Auth->login($this->{$this->model_classname}->getAuthUserData($data[$this->model_classname]['id'])))
            {
                // failed
                throw new Exception(__('Failed bypass authentication.'), 1);
            }

            // passed bypass authentication, assign session data and redirect
            return $this->redirect('/home');
        }

        // check request type
        if (isset($this->request->query['tokenId']))
        {
            // attempt Enterprise AA authentication
            try
            {
                // grab the default group
                $default_group_id = current(
                    Set::extract(
                        '/Group/id',
                        ClassRegistry::init('Accounts.Group')->findByLabel(Configure::read('app.groups.default'))
                    )
                );
                
                // grab the super admin group
                $supder_admin_group_id = current(
                    Set::extract(
                        '/Group/id',
                        ClassRegistry::init('Accounts.Group')->findByLabel(Configure::read('app.groups.super_admin'))
                    )
                );

                // initialize the entaa authentication client
                include_once APP.DS.'Plugin'.DS.'AaAuth'.DS.'Vendor'.DS.'entaa'.DS.'Client.php';
                $entaa_client = new ITE_AAClient_Client(
                    'XML',
                    Configure::read('AaAuth.app_id'),
                    Configure::read('AaAuth.host'),
                    Configure::read('AaAuth.path')
                );

                // set data array
                $data = $this->request;
                $aaUserObj = $entaa_client->getUserObject($this->request->query['tokenId']);
                
                // set the AA user data to session
                $this->Session->write('AaAuth.aa_user_data', $aaUserObj->getUserData());
                
                $data = array($this->model_classname => array('username' => $aaUserObj->getUserId(), 'password' => '*'));
                
                // find the local user data
                $user = $this->{$this->model_classname}->find(
                    'first',
                    array(
                        'conditions' => array(
                            'username' => $data[$this->model_classname]['username'],
                        )
                    )
                );
                
                // if we didn't find a local user record, lets go find one
                if (!$user)
                {
                    // check the default group has login permissions
                    if (!in_array($default_group_id, explode(',', Configure::read('Configuration.allowed_login_groups'))))
                    {
                        throw new Exception(__('You are not permitted to login at this time. Please try again at later time.'), 1);
                    }
                    
                    return $this->redirect(
                        $this->redirect(
                            array(
                                'plugin' => 'accounts', 
                                'controller' => 'accounts', 
                                'action' => 'register'
                            )
                        )
                    );
                }
                else 
                {
                    // if group not super admin check the group has login permissions
                    if ($user[$this->model_classname]['group_id'] != $supder_admin_group_id)
                    {
                        if (!in_array($user[$this->model_classname]['group_id'], explode(',', Configure::read('Configuration.allowed_login_groups'))))
                        {
                            throw new Exception(__('You are not permitted to login at this time. Please try again at later time.'), 1);
                        }
                    }
                    
                    // authenticate the local account
                    if (! $this->Auth->login($this->{$this->model_classname}->getAuthUserData($user[$this->model_classname]['id'])))
                    {
                        // failed
                        throw new Exception(__('Failed local authentication.'), 1);
                    }
                }
            }
            catch (Exception $e)
            {
                // failed insert/update of local account
                $this->Session->setFlash(
                    $e->getMessage(),
                    'default',
                    array(),
                    'auth'
                );
                
                return false;
            }

            // passed authentication, assign session data and redirect
            return $this->redirect('/home');
        }
    }

    /**
     * Format a link to A&A using values defined in site configuration file.
     * Depends upon configuration values
     *     - AaAuth.app_id
     *     - AaAuth.host
     *
     * @param array $params Extra parameters used to craft the A&A link
     *
     * @return string A&A login URL
     * @access private
     */
    private function _generateAALink($params = array())
    {
        if (Configure::read('AaAuth.app_logo'))
        {
            $params[] = sprintf('logo=%s', urlencode(Configure::read('AaAuth.app_logo')));
        }

        $return_url = Router::url($this->Auth->loginAction, true);

        return sprintf(
            'https://%s/entaa/sso%s%s',
            Configure::read('AaAuth.host'),
            Router::queryString(
                null,
                array('appId' => Configure::read('AaAuth.app_id'), 'callingApp' => $return_url)
            ),
            sprintf('&%s', join('&', $params))
        );
    }

    /**
     * logout method
     *
     * Destroy the session and redirect to site home page.
     *
     * @return bool
     */
    public function logout()
    {
        $this->Session->destroy();

        $this->Session->setFlash(__('Thanks for visiting!'));
        return $this->redirect('/');
    }

    /**
     * silentRoutes method
     *
     * Clears the auth error if redirect route matches a silent route defined in the bootstrap.
     *
     * @return bool
     */
    private function _silentRoutes()
    {
        // check for silent routes
        if (! $this->request->is('post'))
        {
            if (in_array($this->Session->read('Auth.redirect'), Configure::read('AaAuth.silent_routes')))
            {
                // remove the auth error
                $this->Session->delete('Message.auth');
            }
        }

        return true;
    }
}
