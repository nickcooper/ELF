<?php
/**
 * OutputDocument Behavior
 *
 * @package App.Model.Behavior
 * @author  Iowa Interactive, LLC.
 */
class OutputDocumentBehavior extends ModelBehavior
{
    /**
     * behavior setup
     *
     * @param array $Model    foreign model
     * @param array $settings defined in the actAs array of the model loading this behavior
     *
     * @return void
     */
    public function setup(Model $Model, $config = array())
    {
        // This overwrites the defualt seetings with the settings being passed
        $this->settings[$Model->name] = (array) $config;
    }

    /**
     * queues documents based on trigger
     *
     * @param Model $Model  foreign model
     * @param array $params contains foreign plugin/object/key, trigger, doc_type, and custom mixed data
     *
     * @return true
     * @access public
     */

    public function queueDocs (Model $Model, $params = array())
    {
        if (empty($params['fp']) || empty($params['fo']) || empty($params['fk']) || empty($params['trigger']))
        {
            throw new Exception(__("Output Document Behavior: queueDocs() missing arguments"));
        }

        $doc_types = array();

        if (!empty($params['doc_type']))
        {
            $doc_types = array($params['doc_type']);
        }
        else
        {
            $config_doc_types = Configure::read(sprintf('OutputDocuments.triggers.%s', $params['trigger']));

            if (is_array($config_doc_types) && count($config_doc_types))
            {
                $doc_types = $config_doc_types;
            }
        }

        // Load in the batch item model
        $this->OutputDocumentBatchItem = ClassRegistry::init('OutputDocuments.OutputDocumentBatchItem');

        foreach ($doc_types as $doc_type)
        {
            // get the foreign data for the document
            try
            {
                $params['doc_type'] = $doc_type['type'];
                $data = $this->getForeignData($Model, $params);
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
            
            $trigger_label = isset($doc_type['label']) ? $doc_type['label'] : false;
            $doc_label = Configure::read(sprintf('OutputDocuments.docs.%s.label', $doc_type['type']));

            $label = 'Unknown Document Label';
            if ($doc_label)
            {
                $label = $doc_label;
            }
            if ($trigger_label)
            {
                $label = $trigger_label;
            }
            
            $this->OutputDocumentBatchItem->queue($params, $label, $data);
        }
        return true;
    }

    /**
     * Method that creates a url for downloading pdf
     *
     * @param Model $Model  foreign model
     * @param array $params contains foreign plugin/object/key, trigger, doc_type, and custom mixed data
     *
     * @return string
     * @access public
     */
    public function buildDocUrl (Model $Model, $params = array())
    {
        // validate the params
        if (empty($params['fp']) || empty($params['fo']) || empty($params['fk']) || empty($params['trigger']) || empty($params['doc_type']) || empty($params['ext']))
        {
            throw new Exception(__("Output Document Behavior: buildDocUrl() missing arguments"));
        }
        
        // build the route
        $bits = array_merge(
            array(
                'plugin' => 'output_documents',
                'controller' => 'output_documents',
                'action' => 'download'
            ),
            $params,
            array(
                'filename' => $params['doc_type'].'_'.time(),
                'ext' => $params['ext']
            )
        );

        // Build the url
        $url = Router::url($bits);
        if ($base = Configure::read('App.base'))
        {
            $url = preg_replace("%^$base%", '', $url, 1);
        }
        
        $url = preg_replace('/filename\:/', '', $url);
        
        // return the formatted link url
        return $url;
    }

    /**
     * Generate document download links for a specific model item.
     *
     * @param Model $Model  Foreign model
     * @param array $params Parameters
     *
     * @return array Document links
     * @access public
     */
    public function generateDocLinks(Model $Model, $params = array())
    {
        if (! isset($Model->actsAs['OutputDocuments.OutputDocument']))
        {
            throw new Exception(__('Output Document Behavior: generateDocLinks() invalid model.'));
        }

        $defaults = array(
            'fp' => $Model->pluginName,
            'fo' => $Model->name,
        );

        // generate doc links array
        $params = array_merge($defaults, $params);

        $links = array();
        foreach (Configure::read(sprintf('OutputDocuments.triggers.%s', $params['fo'])) as $trigger => $doc_type_keys)
        {
            foreach ($doc_type_keys as $doc_type)
            {
                $doc_type_data = Configure::read(sprintf('OutputDocuments.docs.%s', $doc_type));
                $params['trigger'] = $trigger;
                $params['doc_type'] = $doc_type;
                $links[$doc_type_data['label']] = $Model->buildDocUrl($params);
            }
        }

        return $links;
    }

    /**
     * Method that gets data from other plugins for placeholders
     *
     * @param Model $Model  foreign model
     * @param array $params contains foreign plugin/object/key, trigger, doc_type, and custom mixed data
     *
     * @return string
     * @access public
     */
    public function getForeignData (Model $Model, $params = array())
    {
        try
        {
            if (empty($params['fp']) || empty($params['fo']) || empty($params['fk']) || empty($params['trigger']) || empty($params['doc_type']))
            {
                throw new Exception(__("Output Document Behavior: getForeignData() missing arguments"));
            }

            $method = Configure::read(sprintf('OutputDocuments.docs.%s.data', $params['doc_type']));

            if (!$method)
            {
                throw new Exception(__("Output Document Behavior: getForeignData() data method not found in settings."));
            }

            $data = $Model->{$method}($params);

            if (!is_array($data))
            {
                throw new Exception(__("Output Document Behavior: getForeignData() problem getting foreign data."));
            }
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }

        return $data;
    }
}