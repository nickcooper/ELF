<?php
class LegacyObj
{
    /**
     * 
     */
    public function yesNo ($value = null, $legacy_data = array())
    {
        
    }
    
    /**
     * caseWords method
     * 
     * Lowercase string then run ucwords.
     */
    public function caseWords ($value = null, $legacy_data = array())
    {
        return ucwords(strtolower($value));
    }
    
    /**
     * numericOnly method
     */
    public function numericOnly ($value = null, $legacy_data = array())
    {
        return preg_replace('/[^0-9]/', '', $value);
    }
    
    /**
     * Translates m/d/Y HH:MM:SS timestamp to YYYY-MM-DD HH:MM:SS
     *
     * @param string $val m/d/Y HH:MM:SS timestamp
     *
     * @return string Timestamp converted to YYYY-MM-DD HH:MM:SS
     * @access public
     */
    public function translateDateTime($val, $legacy_data = array())
    {
        $datetime =
            '/'.
            '(?P<month>[0-9]{1,2})'.
            '\/'.
            '(?P<day>[0-9]{1,2})'.
            '\/'.
            '(?P<year>[0-9]{2,4})'.
            ' '.
            '(?P<hour>[0-9]{1,2}):(?P<minute>[0-9]{1,2}):(?P<second>[0-9]{1,2})'.
            '/';

        $date =
            '/'.
            '(?P<month>[0-9]{1,2})'.
            '\/'.
            '(?P<day>[0-9]{1,2})'.
            '\/'.
            '(?P<year>[0-9]{2,4})'.
            '/';

        foreach (compact('datetime', 'date') as $type => $regex)
        {
            if (preg_match($regex, $val, $matches))
            {
                if (strlen($matches['year']) == 2)
                {
                    // assume XX years that occur more than 5 years in the future occured in 19xx
                    $matches['year'] = ($matches['year'] > date('y')+5)
                        ? 1900 + $matches['year']
                        : 2000 + $matches['year'];
                }

                switch ($type) {

                case 'date':
                    return sprintf('%d-%02d-%02d', $matches['year'], $matches['month'], $matches['day']);
                    break;

                case 'datetime':
                    return sprintf(
                        '%d-%02d-%02d %02d:%02d:%02d',
                        $matches['year'], $matches['month'], $matches['day'],
                        $matches['hour'], $matches['minute'], $matches['second']
                    );
                    break;
                }
            }
        }

        return $val;
    }
}
?>