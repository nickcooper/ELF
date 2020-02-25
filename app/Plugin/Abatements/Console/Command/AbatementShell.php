<?php

/**
 * AbatementShell
 * 
 * @package    App
 * @subpackage App.Console.Command
 * @author     Iowa Interactive, LLC.
 */
class AbatementShell extends Shell
{
    /**
     * tasks
     *
     * @var array
     * @access public
     */
    public $tasks = array(
        'Abatements.ReportReminder',
        'Abatements.StatusChange',
    );

    /**
     * Possible tasks.
     *
     * @var array
     * @access private
     */
    private $_possibleTasks = array(
        /*'notification',*/
        'report_reminder',
    );

    /**
     * Gets the option parser instance and configures it.  By overriding this method
     * you can configure the ConsoleOptionParser before returning it.
     *
     * @return ConsoleOptionParser
     * @access public
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addArgument(
            'task',
            array(
                'help' => __('Task to perform'),
                'choices' => $this->_possibleTasks,
                /*'required' => true,*/
            )
        );

        return $parser;
    }

    /**
     * Main entry point.
     * 
     * @return void
     * @access public
     */
    public function main()
    {
        try
        {
            switch (array_shift($this->args)) {

            case 'notification':
                $this->StatusChange->execute();
                break;

            case 'report_reminder':
                $this->ReportReminder->execute();
                break;

            default:
                throw new Exception(
                    sprintf(__('Please specify task (choices: <info>%s</info>)'), join(' ', $this->_possibleTasks))
                );

            }
        }
        catch (Exception $e)
        {
            $this->error($e->getMessage());
            exit(1);
        }

        exit(0);
    }
}
