<?php
/**
 * Searchable Behavior.
 *
 * Enables a model to act as a searchable foreign object for the
 * Searches plugin.
 *
 * Actions:
 *  Locator - Find a single record from the foreign object table and return it's id.
 *  Index   - General listing of records for foreign object table. Includes keyword filtering.
 *  Add     - Ability to add a new record to the foreign object table. Uses foreign object model
 *            method and form element.
 *
 *
 * Configurations are required if you are using that particular Searchable action. If you are only using
 * Searchable Index you need only define that configuration in the model.
 *
 *
 * Settings:
 *  locator
 *      title       - Replaces the text at the top of the Searchable locator page.
 *      search      - Which fields to keyword search, includes associative data with the exception of
 *                    hasMany relations.
 *      fields      - Which fields to display in the results table. Array key is used as column heading.
 *      contain     - Optional table joins. Uses Model->contain as default.
 *      conditions  - Cake query conditional settings.
 *      order       - Order by for results.
 *      limit       - Maximum number of results. Pagination is not used at this time so a maximum is required.
 *
 *  index
 *      title       - Replaces the text at the top of the Searchable locator page.
 *      fields      - Which fields to display in the results table. Array key is used as column heading.
 *      contain     - Optional table joins. Uses Model->contain as default.
 *      conditions  - Cake query conditional settings.
 *      order       - Order by for results.
 *      elements    - Which foreign object view elements to include. Array key is Searchable view var name
 *                    and value is foreign object element in View/Elements/.
 *      view_vars   - View variables for the foreign object elements above. Array key is variable name
 *                    and value is either foreign object method that returns the variable data or view data.
 *
 *  add
 *      title       - Replaces the text at the top of the Searchable add page.
 *      method      - Foreign object method responsible for processing and saving the new record data to
 *                    the foreign object table.
 *      elements    - Which foreign object view elements to include. Array key is Searchable view var name
 *                    and value is foreign object element in View/Elements/.
 *      view_vars   - View variables for the foreign object elements above. Array key is variable name
 *                    and value is foreign object method that returns the variable data.
 *
 *  paginate
 *      limit       - Total number of results per page
 *
 * @package App.Model.Behavior
 * @author  Iowa Interactive, LLC.
 */
class SearchableBehavior extends ModelBehavior
{
    /**
     * behavior setup
     *
     * @param array $Model    cakephp handles this param
     * @param array $settings defined in the actAs array of the model loading this behavior
     *
     * @return void
     * @access public
     */
    public function setup(Model $Model, $settings=array())
    {
        // read in the agency-specific searchable configuration
        $settings = Configure::read(sprintf('Searchable.%s', $Model->alias));

        // default settings - see documentation above
        if (! isset($this->settings[$Model->alias]))
        {
            $this->settings[$Model->alias] = array(
                'searchable' => array(
                    'title'      => null,
                    'search'     => array(),
                    'fields'     => array(),
                    'contain'    => array(),
                    'conditions' => array(),
                    'order'      => array(sprintf('%s.modified', $Model->alias) => 'DESC'),
                    'elements'   => array(),
                    'view_vars'  => array(),
                ),
                'add' => array(
                    'title'     => null,
                    'method'    => 'add',
                    'element'  => '',
                    'view_vars' => array(),
                ),
                'paginate' => array(
                    'limit'     => 10,
                    'page'      => 1,
                )
            );
        }

        // overwrite default settings with passed settings
        foreach ($this->settings[$Model->alias] as $key => $val)
        {
            foreach ($val as $key2 => $val2)
            {
                if (isset($settings[$key][$key2]))
                {
                    $this->settings[$Model->alias][$key][$key2] = $settings[$key][$key2];
                }
            }
        }

        // overwrite default settings with passed settings
        if ($settings)
        {
            $this->settings[$Model->alias] = array_replace_recursive($this->settings[$Model->alias], $settings);
        }

        // Behavior settings aren't available outside the behaivor
        // we'll need to access these for laying out the view results
        // So assign them back to the foreign model so the Searchable
        // controller can get them.
        $Model->Searchable = $this->settings[$Model->alias];
    }

    /**
     * findMostAccurate method
     *
     * @param obj &$Model   cakephp handles this param on it's own
     * @param str $keywords expecting keyword string stripped of any non-alpha-numeric characters
     *
     * @return array returns query conditions
     * @access public
     */
    public function findMostAccurate(&$Model = null, $keywords = array())
    {
        $settings =& $this->settings[$Model->alias];

        //$tmp_keywords = array('%'.$keywords.'%');
        $tmp_keywords = $this->filterKeywords(array(trim($keywords)));

        // define the conditions and add exclusions if defined
        $conditions = array(array('OR' => $this->groupKeywordConditions($settings['searchable']['search'], $tmp_keywords)));

        return $conditions;
    }

    /**
     * findLessAccurate method
     *
     * @param obj &$Model   cakephp handles this param on it's own
     * @param str $keywords expecting keyword string stripped of any non-alpha-numeric characters
     *
     * @return array returns query conditions
     * @access public
     */
    public function findLessAccurate(&$Model = null, $keywords = array())
    {
        $settings =& $this->settings[$Model->alias];

        $keywords = explode(',', $keywords);

        // filter the keywords
        foreach ($keywords as $keyword)
        {
            $tmp_keywords[] = $this->filterKeywords(explode(' ', trim($keyword)));
        }

        // define the conditions
        $conditions = array(
            array(
                'OR' => $this->groupKeywordConditions($settings['searchable']['search'], $tmp_keywords)
            )
        );

        return $conditions;
    }

    /**
     * findLeastAccurate method
     *
     * @param obj &$Model   cakephp handles this param on it's own
     * @param str $keywords expecting keyword string stripped of any non-alpha-numeric characters
     *
     * @return array returns query conditions
     * @access public
     */
    public function findLeastAccurate(&$Model = null, $keywords = array())
    {
        $settings =& $this->settings[$Model->alias];

        $keywords = array_unique(explode(' ', $keywords));

        // filter the keywords
        foreach ($keywords as $keyword)
        {
            $tmp_keywords[] = $this->filterKeywords(explode(' ', trim($keyword)));
        }

        // define the conditions
        $conditions = array(
            array(
                'OR' => $this->groupKeywordConditions($settings['searchable']['search'], $tmp_keywords)
            )
        );

        return $conditions;
    }

    /**
     * validateSearchable method
     *
     * @param obj &$Model cakephp handles this param on it's own
     *
     * @return bool
     * @access public
     *
     * @throws Exception If virtual fields are configured to be searchable.
     * @throws Exception If a specified field in the configuration is not in the model's schema.
     */
    public function validateSearchable(&$Model = null)
    {
        // define the setting locally
        $settings =& $this->settings[$Model->alias];

        // merge all of the search fields and order fields together
        $fields = array_unique(
            array_merge(
                $settings['search']['fields'],
                array_keys($settings['search']['order'])
            )
        );

        // get a list of table fields, include any virtual fields
        $tbl_fields = array_merge(array_keys($Model->schema()), array_keys($Model->virtualFields));

        // validate the (fields, exclude, order) fields do exist
        foreach ($fields as $field)
        {
            $bits = explode('.', $field);

            // if count bits is > 1 make sure the model matches or skip it
            // because some conditions may apply to joined tables
            if (count($bits) > 1 && $bits[0] != $Model->alias)
            {
                continue; // skip to the next field
            }

            // strip out the model name if needed
            $field = preg_replace('/^[a-z]+\./i', '', $field);

            // make sure searchable fields are not virtual fields
            $isVirtualField = in_array($field, array_keys($Model->virtualFields));
            if (in_array($field, $settings['search']['fields']) && $isVirtualField)
            {
                // fail
                throw new Exception(
                    __('Invalid Searchable field configuration. Virtual fields are not searchable.')
                );
            }

            // compare the fields
            if (! in_array($field, $tbl_fields))
            {
                // fail
                throw new Exception(__('Invalid Searchable field configuration.'));
            }
        }

        // passed
        return true;
    }

    /**
     * groupKeywordConditions method
     *
     * Groups each condition together w/ AND
     *
     * @param array $fields        expecting searchable fields array
     * @param array $keyword_group expecting keyword groups array
     *
     * @return array returns formatted conditions array
     * @access public
     */
    public function groupKeywordConditions($fields = array(), $keyword_group = array())
    {
        // convert keyword strings to array
        if (is_string($keyword_group))
        {
            $keyword_group = array($keyword_group);
        }

        // default return conditions
        $conditions = array();

        foreach ($keyword_group as $keywords)
        {
            // convert keyword string to array
            if (is_string($keywords))
            {
                $keywords = array($keywords);
            }

            foreach ($fields as $field)
            {
                // begin the grouped statement
                $cond = sprintf('(REPLACE(%s, " ", "") LIKE "', $field);

                foreach ($keywords as $keyword)
                {
                    if (is_string($keyword))
                    {
                        $keyword = array($keyword);
                    }

                    // append the condition to the statement
                    foreach ($keyword as $word)
                    {
                        $cond .= '%'.$word.'%" AND REPLACE('.$field.', " ", "") LIKE "%';
                    }
                }

                // strip off the last AND becuase we've ended the loop
                $cond = preg_replace('/\" AND REPLACE\('.$field.'\, \"\ \"\, \"\"\) LIKE \"%$/i', '', $cond);

                // replace any double %% with a single %
                $cond = preg_replace('/\%+/i', '%', $cond);

                // end the grouped statement
                $cond .= '")';

                // add condition to return conditions array
                $conditions[] = $cond;
            }
        }

        return $conditions;
    }

    /**
     * filterKeywords method
     *
     * Replaces any non-alphanumeric characters with percent signs.
     * This makes it easier to create the mysql LIKE statements.
     * This also makes the searches more accurate in that searching
     * for A1 will match A-1.
     *
     * @param array $keywords expecting keywords array
     *
     * @return array returns filtered keywords array
     * @access public
     */
    public function filterKeywords($keywords = array())
    {
        // replace patterns
        $pat = array();                                 $rep = array();
        $pat[] = '/\s/i';                               $rep[] = ''; // spaces
        $pat[] = '/([^a-z0-9\&\,\-])/i';    $rep[] = '%'; // non alphnumeric, ampersan, comma, hyphen => %
        $pat[] = '/\%+/';                               $rep[] = '%'; // %% => %

        foreach ($keywords as $key => $keyword)
        {
            if (is_string($keyword))
            {
                $keywords[$key] = strtolower(preg_replace($pat, $rep, trim($keyword)));

                if ($keywords[$key] == '%')
                {
                    $keywords[$key] = 'never going to match this string 23409357y02'; // force a mis match of value that end up being empty (ei. '%')
                }
            }
            elseif (is_array($keyword))
            {
                $keywords[$key] = $this->filterKeywords($keyword);
            }
        }

        return array_unique($keywords);
    }

    /**
     * filterConditions method
     *
     * @param obj   &$Model  cakephp handles this param on it's own
     * @param array $filters expecting filter form post values
     *
     * @return array returns an array of cake pagination conditions
     * @access public
     */
    public function filterConditions (&$Model=null, $filters=array())
    {
        $conditions = array();

        // process flags
        if (isset($filters['flags']) && !empty($filters['flags']))
        {
            // explode the flags
            $flags = explode(',', $filters['flags']);

            // loop the flags
            foreach ($flags as $flag)
            {
                // append the flag field and value to the $filters array
                $filters[$flag] = 1;
            }
        }

        foreach ($filters as $filter => $val)
        {
            // exclude empty values
            if (! is_array($val))
            {
                if (! preg_match('/[a-z0-9]+/i', $val))
                {
                    continue;
                }
            }

            // get the model.field from search settings.
            $model_field = false;
            foreach ($Model->Searchable['searchable']['search'] as $setting)
            {
                if (preg_match(sprintf('/\.%s/', strtolower($filter)), $setting))
                {
                    $model_field = $setting;
                }
            }

            // check to see if the filter param is a searchable field and it not the dynamic date field
            if ($filter != 'date_field' AND !$model_field)
            {
                // if not skip it
                continue;
            }

            // filter the filters?
            switch ($filter)
            {
            case 'date_field' :
                if (preg_match('/[a-z0-9]+/', $val))
                {
                    // reformat the date input fields from searchable so they will be equivalent
                    // when we use them for comparisons later
                    if (!empty($filters['date_start']) || !empty($filters['date_end']))
                    {
                        // reformat the start date
                        $start_date = $this->convertDate($filters['date_start'], 1);

                        // write the reformatted start date to the agency's configuration
                        $config_path = sprintf('Searchable.%s.searchable.view_vars.start_date', $Model->alias);
                        Configure::write($config_path, $start_date);

                        // reformat the end date
                        $end_date = $this->convertDate($filters['date_end'], 2);

                        // write the reformatted end date to the agency's configuration
                        $config_path = sprintf('Searchable.%s.searchable.view_vars.end_date', $Model->alias);
                        Configure::write($config_path, $end_date);
                    }

                    if (!empty($start_date) && empty($end_date))
                    {
                        $conditions['AND'][] = array(
                            sprintf(
                                '%s >= "%s"',
                                $val,
                                $start_date
                            )
                        );
                    }
                    if (empty($start_date) && !empty($end_date))
                    {
                        $conditions['AND'][] = array(
                            sprintf(
                                '%s <= "%s"',
                                $val,
                                $end_date
                            )
                        );
                    }
                    if (!empty($start_date) && !empty($end_date))
                    {
                        $conditions['AND'][] = array(
                            sprintf(
                                '%s BETWEEN "%s" AND "%s"',
                                $val,
                                $start_date,
                                $end_date
                            )
                        );
                    }
                }
                break;

            default:
                $tmp = array();
                if (! is_array($val))
                {
                    if (strpos($val, ',') !== false)
                    {
                        $val = explode(',', $val);
                    }
                    $tmp[] = array($model_field => $val);
                }
                elseif (count($val) == 1)
                {
                    if (preg_match('/[a-z0-9]+/i', $val[0]))
                    {
                        $tmp[] = array($model_field => $val[0]);
                    }
                }
                else
                {
                    foreach ($val as $v)
                    {
                        if (preg_match('/[a-z0-9]+/i', $v))
                        {
                            $tmp['OR'][] = array($model_field => $v);
                        }
                    }
                }

                if ($tmp)
                {
                    $conditions['AND'][] = $tmp;
                }
                break;
            }
        }

        return $conditions;
    }

    /**
     * Retrieves methods used for generating condtions for various search
     * accuracies. Methods returned must be defined in this class with the
     * format `find{$accuracy}Accurate`. This method exists so we don't have
     * to hardcode 'most', 'less', or 'least' everywhere.
     *
     * @return array methods
     * @access public
     */
    public function getSearchAccuracyMethods()
    {
        $filterFunction = function($method)
        {
            if (preg_match('/Accurate$/', $method))
            {
                return $method;
            }
        };

        $accuracies = array_values(
            array_filter(get_class_methods($this), $filterFunction)
        );

        rsort($accuracies);

        return $accuracies;
    }

    /**
     * convertDate(date $date, string $type)
     * Converts a passed in date field in format MM/DD/YYYY to format YYYY/MM/DD.
     *
     * @param string $date a comma-delimited array
     * @param int    $type an integer defining the date type (1 = start date | 2 = end date)
     *
     * @return string the reformatted date
     * @access public
     */
    public function convertDate($date = '1/1/1900', $type = 1)
    {
        // break out the individual date fields (MM/DD/YYYY) so the date can be reformatted
        $tmp_date = explode(',', $date);

        // get a count of the total fields in the date
        $count = count($tmp_date);

        // if the count = 2, then the numerical month value from the Searchable form equals
        // the day value (i.e. January 1 = 1/1/2013, February 2 = 2/2/2013, March 3 = 3/3/2013),
        // and cake suppressed one of the values for some unknown reason (1/2013, 2/2013, 3/2013).
        // We need to duplicate to create a valid MM/DD/YYYY value
        if ($count == 2)
        {
            $tmp_date[2] = $tmp_date[1];
            $tmp_date[1] = $tmp_date[0];
        }

        // evaluate the type parameter to set the time value for the reformatted date
        if ($type == 2)
        {
            $time = '23:59:59';
        }
        else
        {
            $time = '00:00:00';
        }

        // reformat the date
        if (!empty($tmp_date[0]) && !empty($tmp_date[1]) && !empty($tmp_date[2]))
        {
            $reformatted_date = sprintf('%s-%s-%s %s', $tmp_date[2], $tmp_date[0], $tmp_date[1], $time);
        }
        else
        {
            $reformatted_date = '';
        }

        return $reformatted_date;
    }
}