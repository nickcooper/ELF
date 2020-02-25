<?php

/**
 * Searchable Controller
 *
 * This controller uses the ForeignObject component
 * which does a lot of foreign obj stuff automatically
 * for us. If you can't find where a variable is being
 * defined it may be done by the ForeignObject component.
 * See the documentation for details.
 *
 * @package Search.Controllers
 * @author  Iowa Interactive, LLC.
 */
class SearchableController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Searchable';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array();

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array(
        'ForeignObject' => array('validate' => false),
    );

    /**
     * @var array
     */
    public $helpers = array('Text');

    /**
     * beforeFilter method
     *
     * @return void
     * @access public
     *
     * @todo update searchable locator form to use the form helper so we don't have to allow it through the blackhole
     * @todo Refreshing the page after search causes csrf check errors
     */
    public function beforeFilter()
    {
        // Disabling CSRF checks in the security component for Searchable because it causes a fair number of
        // problems and should be tested well to make sure we don't cause problems that would impact
        // the application because of how integrated the plugin is throughout
        $this->Security->csrfCheck = false;

        // parent function
        parent::beforeFilter();
    }

    /**
     * index method
     *
     * @return void
     * @access public
     */
    public function index()
    {
        // update the named params with any new settings
        if (isset($this->request->data['Filter']))
        {
            $options = array_merge($this->params->named, $this->request->data['Filter']);

            foreach ($options as $key => $val)
            {
                if (!is_array($val))
                {
                    $val = array($val);
                }

                // replace slashes with hyphens, otherwise the url breaks
                foreach ($val as $k => $v)
                {
                    $val[$k] = preg_replace('/\//', '-', $v);
                }

                $options[$key] = implode(',', array_unique($val));
            }

            // this will redirect back here but with the newly updated named params in the url
            $this->redirect($options, null, true, 'forward');
        }

        // set the behavior settings locally in the controller
        $settings = $this->ForeignModel->Searchable;

        // if searchable param redirect to FO view page
        if (isset($this->request->params['named']['searchable']))
        {
            $this->redirect(
                array(
                    'plugin'     => $this->underscored_foreign_plugin,
                    'controller' => $this->foreign_controller,
                    'action'     => 'view',
                    $this->request->params['named']['searchable'],
                )
            );
        }

        // Clone the foreign object model as Searchable so we can use paginate
        $this->Searchable = clone $this->ForeignModel;

        // get the query options
        $options = $this->queryOptionBuilder($settings);

        // apply any configured filters defined in the searchable configs
        $config_filter = array();

        if (isset($this->request->params['named']['cf']))
        {
            $config_filter = $this->_getConfigFilterInfo($this->request->params['named'], $settings);
        }

        foreach ($options as $method => $options)
        {
            // do not include FO data if model is License
            $this->Searchable->includeForeignData = false;

            // merge the options
            $this->paginate = array_merge_recursive($options, compact('conditions'), $config_filter);

            // grab some data
            if ($results = $this->paginate())
            {
                // if we found data don't bother running the other searches
                break;
            }
        }
        $this->set('results', $results);

        // set the keyword searchable fields
        $searchable_keyword_fields = array();
        foreach ($settings['searchable']['fields'] as $field => $options)
        {
            if (isset($options['label']) && preg_match('/[a-z]/i', $options['label']))
            {
                $searchable_keyword_fields[] = $options['label'];
            }
            else
            {
                $searchable_keyword_fields[] = $field;
            }
        }
        $this->set('searchable_keyword_fields', $searchable_keyword_fields);

        // set the fields to display
        $this->set('fields', $settings['searchable']['fields']);

        // do we need to set any special view elements
        $this->_setViewElements('searchable');

        // do we need to set any special view vars
        $this->_setViewVars('searchable');

        // set the title
        $title = $settings['searchable']['title']
            ? $settings['searchable']['title']
            : Inflector::pluralize($this->humanized_foreign_obj);
        $this->set('title', $title);
    }

    /**
     * download method
     *
     * Export searchable results to csv format.
     *
     * @return void
     * @access public
     */
    public function download()
    {
        // set the behavior settings locally in the controller
        $settings = $this->ForeignModel->Searchable;

        // Clone the foreign object model as Searchable so we can use paginate
        $this->Searchable = clone $this->ForeignModel;

        // get the query options
        $options = $this->queryOptionBuilder($settings);

        foreach ($options as $method => $options)
        {
            // force the limit
            $options['offset'] = null;
            $options['limit'] = 1000;

            // do not include FO data if model is License
            $this->Searchable->includeForeignData = false;

            // merge the options
            $options = array_merge_recursive($options, compact('conditions'));

            // grab some data
            if ($results = $this->Searchable->find('all', $options))
            {
                // if we found data don't bother running the other searches
                break;
            }
        }

        // loop the records and format the data according to configured fields
        $data = array();

        // define the file headings
        $columns = $this->Searchable->Searchable['searchable']['download_fields'];

        $data[] = sprintf('"%s"', implode('","', Hash::extract($columns, '{s}.label')));

        foreach ($results as $result)
        {
            $values = array();
            foreach ($columns as $key => $config)
            {
                $values[] = Hash::get($result, $key);
            }

            $data[] = sprintf('"%s"', implode('","', $values));
        }

        // pass the data to the view
        $this->set('data', $data);
    }

    /**
     * query option builder
     *
     * @param array $settings expecting FO model's searchable settings array
     *
     * @return array
     * @access private
     */
    private function queryOptionBuilder ($settings = array())
    {
        // default return
        $method_options = array();

        // loop the Searchable search methods
        foreach ($this->Searchable->getSearchAccuracyMethods() as $method)
        {
            // get a list of options for the query
            $options = array_merge(
                array(
                    'conditions' => array(),
                    'contain'    => array(),
                    'joins'      => array(),
                    'order'      => array(),
                    'offset'     => null,
                    'limit'      => null,
                    'page'       => null,
                ),
                $settings['paginate']
            );

            // add foreign obj model Searchable settings
            foreach ($settings['searchable'] as $key => $val)
            {
                if (array_key_exists($key, $options))
                {
                    $options[$key] = $val;
                }
            }

            // Build the select list from models listed in the fields and download_fields
            $models = array_keys(Hash::expand($settings['searchable']['fields']));

            if (isset($settings['searchable']['download_fields']))
            {
                $models = array_merge($models, array_keys(Hash::expand($settings['searchable']['download_fields'])));
                $models = array_unique($models);
            }

            foreach ($models as $model)
            {
                $options['fields'][] = sprintf('%s.*', $model);
            }

            // add filter keywords
            if (isset($this->params->named['keywords']) && !empty($this->params->named['keywords']))
            {
                $conditions = call_user_func(array($this->Searchable, $method), $this->params->named['keywords']);
                $options = array_merge_recursive($options, compact('conditions'));
            }

            // add filter options
            $options = array_merge_recursive(
                $options,
                array('conditions' => $this->Searchable->filterConditions($this->params->named))
            );

            $method_options[$method] = $options;
        }

        return $method_options;
    }

    /**
     * add method
     *
     * @return void
     * @access public
     */
    public function add()
    {
        // set the behavior settings locally in the controller
        $settings = $this->ForeignModel->Searchable;

        // validate Searchable is configured correctly
        //$this->_validateSearchable($settings, 'add');

        // check for the searchable add post
        if ($this->request->is('post'))
        {
            try
            {
                // attempt to save the foreign obj data
                $this->ForeignModel->{$settings['add']['method']}($this->request->data);

                // parse the return route
                $return = Router::parse(base64_decode($this->params['named']['return']));

                // add the last insert id to the route
                $return['named']['searchable'] = $this->ForeignModel->getInsertID();

                // move the named key/value pairs up one and remove the named and pass sub arrays
                $return = array_merge($return, $return['named']);
                $return = array_merge($return, $return['pass']);
                unset($return['named'], $return['pass']);

                // redirect back to origin
                $this->redirect(
                    $return,
                    null,
                    false,
                    'skip'
                );
            }
            catch (Exception $e)
            {
                // fail
                $this->Session->setFlash($e->getMessage());
                $this->redirect();
            }
        }

        // do we need to set any special view vars
        $this->_setViewVars('add');

        // set the foreign obj add form element for the view
        $this->set('add_form_element', $settings['add']['element']);

        // set the title
        $title = $settings['add']['title']
            ? $settings['add']['title']
            : sprintf(__('Add New %s'), $this->humanized_foreign_obj);
        $this->set('title', $title);
    }

    /**
     * locator method
     *
     * @return void
     * @access public
     */
    public function locator()
    {
        // if the foreign key was provided no need to search for one, redirect!
        if ($this->foreign_key || isset($this->params['named']['searchable']))
        {
            $id = $this->foreign_key;
            if (isset($this->params['named']['searchable']))
            {
                $id = $this->params['named']['searchable'];
            }

            $loc_array = array_merge_recursive(
                Router::parse(base64_decode($this->params['named']['return'])),
                array('named' => array('searchable' => $id))
            );

            // add the route
            $loc_str = sprintf(
                '/%s/%s/%s/',
                $loc_array['plugin'],
                $loc_array['controller'],
                $loc_array['action']
            );

            // add the passed params
            $loc_str .= implode('/', $loc_array['pass']);

            // add the named params
            foreach ($loc_array['named'] as $key => $val)
            {
                $loc_str .= sprintf('/%s:%s', $key, $val);
            }

            $this->redirect(
                $loc_str,
                null,
                true,
                'skip'
            );
        }

        // update the named params with any new settings
        if (isset($this->request->data['Filter']))
        {
            $options = array_merge($this->params->named, $this->request->data['Filter']);

            foreach ($options as $key => $val)
            {
                if (!is_array($val))
                {
                    $val = array($val);
                }

                // replace slashes with hyphens, otherwise the url breaks
                foreach ($val as $k => $v)
                {
                    $val[$k] = preg_replace('/\//', '-', $v);
                }

                $options[$key] = implode(',', array_unique($val));
            }

            // this will redirect back here but with the newly updated named params in the url
            $this->redirect($options, null, true, 'forward');
        }

        // set the behavior settings locally in the controller
        $settings = $this->ForeignModel->Searchable;

        // Clone the foreign object model as Searchable so we can use paginate
        $this->Searchable = clone $this->ForeignModel;

        // get the query options
        $options = $this->queryOptionBuilder($settings);

        // apply any configured filters defined in the searchable configs
        $config_filter = array();

        if (isset($this->request->params['named']['cf']))
        {
            $config_filter = $this->_getConfigFilterInfo($this->request->params['named'], $settings);
        }

        $results = false;

        foreach ($options as $method => $options)
        {
            // skip if there isn't any conditions to filter by
            if (!GenLib::isData($options, 'conditions'))
            {
                continue;
            }

            // merge the config filters with the param filters
            $options = array_merge_recursive($options, $config_filter);

            // do not include FO data if model is License
            $this->Searchable->includeForeignData = false;

            // merge the options
            $this->paginate = array_merge_recursive($options, compact('conditions'));

            // grab some data
            if ($results = $this->paginate())
            {
                // if we found data don't bother running the other searches
                break;
            }
        }

        $this->set('results', $results);

        // set the keyword searchable fields
        $searchable_keyword_fields = array();
        foreach ($settings['searchable']['fields'] as $field => $options)
        {
            if (preg_match('/[a-z]/i', $options['label']))
            {
                $searchable_keyword_fields[] = $options['label'];
            }
        }
        $this->set('searchable_keyword_fields', $searchable_keyword_fields);

        // set the fields to display
        $this->set('fields', $settings['searchable']['fields']);

        $settings_type = (isset($this->request->params['named']['cf'])) ? $this->request->params['named']['cf'] : 'searchable';

        // do we need to set any special view elements
        $this->_setViewElements($settings_type);

        // do we need to set any special view vars
        $this->_setViewVars($settings_type);

        // set the title
        $title = $settings['searchable']['title']
            ? $settings['searchable']['title']
            : Inflector::pluralize($this->humanized_foreign_obj);
        $this->set('title', $title);

        // add new record option
        $add_new = null;

        // set the add_new view var only when coming to locator from the licenses plugin
        if (!preg_match('/firms/i', base64_decode($this->request->params['named']['return'])))
        {
            if (GenLib::isData($settings, 'add', array('method')))
            {
                $add_new = $settings['add']['method'];
            }
        }
        $this->set('add_new', $add_new);


        $this->set('description', false);
    }

    /**
     * _setViewElements method
     *
     * @param string $settings_type settings type
     *
     * @return void
     * @access private
     */
    private function _setViewElements($settings_type)
    {
        // set the behavior settings locally in the controller
        $settings = $this->ForeignModel->Searchable[$settings_type];

        // set foreign obj view elements
        if (isset($settings['elements']))
        {
            foreach ($settings['elements'] as $var_name => $element_name)
            {
                $this->set($var_name, $element_name);
            }
        }
    }

    /**
     * _setViewVars method
     *
     * @param string $settings_type settings type
     *
     * @return void
     * @access private
     */
    private function _setViewVars($settings_type)
    {
        // set the behavior settings locally in the controller
        $settings = $this->ForeignModel->Searchable[$settings_type];
        $this->set('searchable_settings', $settings);

        // set foreign obj view vars
        if (isset($settings['view_vars']))
        {
            foreach ($settings['view_vars'] as $var_name => $value)
            {
                if (!is_array($value) && method_exists($this->ForeignModel, $value))
                {
                    $this->set($var_name, $this->ForeignModel->{$value}());
                }
                else
                {
                    $this->set($var_name, $value);
                }
            }
        }
    }

    /**
     * _validateSearchable method
     *
     * Validates that the
     *
     * @return array returns foreign model data records
     * @access private
     *
     * @throws Exception If foreign model is not configured with a Searchable behavior.
     */
    private function _validateSearchable()
    {
        try
        {
            // check that foreign model actsAs Searchable
            if (!array_key_exists('Searchable.Searchable', $this->ForeignModel->actsAs))
            {
                // fail
                throw new Exception(
                    sprintf(
                        __('Foreign model (%s.%s) does not act as Searchable.'),
                        $this->foreign_plugin,
                        $this->foreign_obj
                    )
                );
            }

            // validate the searchable behavior is configured correctly
            $this->ForeignModel->validateSearchable($this->ForeignModel);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * _getConfigFilterInfo method
     *
     * Parses the keyword params in the URL for the custom filter key, and returns the associated
     * information related to the custom filter key
     *
     * @param array  $params   params array
     * @param string $settings foreign object's searchable configuration
     *
     * @return an array of information related to the provided custom filter key
     * @access private
     *
     * @throws Generic Exception
     */
    private function _getConfigFilterInfo($params, $settings)
    {
        $custom_filter = array();

        try
        {
            if (isset($params['cf']) && isset($this->ForeignModel->Searchable[$params['cf']]))
            {
                $custom_filter = $this->ForeignModel->Searchable[$params['cf']];
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }

        return $custom_filter;
    }
}