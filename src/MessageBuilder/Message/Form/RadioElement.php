<?php

namespace OpenDialogAi\MessageBuilder\Message\Form;

class RadioElement extends BaseElement
{
    public $name;
    public $display;
    public $options;
    public $required;
    public $defaultValue;

    /**
     * RadioElement constructor.
     * @param $name
     * @param $display
     * @param $options
     * @param $required
     * @param $defaultValue
     */
    public function __construct($name, $display, $options, $required, $defaultValue = '')
    {
        $this->name = $name;
        $this->display = $display;
        $this->options = $options;
        $this->required = ($required) ? 'true' : 'false';
        $this->defaultValue = $defaultValue;
    }

    public function getMarkUp()
    {
        $optionsMarkUp = '';

        foreach ($this->options as $option) {
            $optionsMarkUp .= '<option><key>' . $option['key'] . '</key>';
            $optionsMarkUp .= '<value>' . $option['value'] . '</value></option>';
        }

        return <<<EOT
<element required="$this->required">
    <element_type>radio</element_type>
    <name>$this->name</name>
    <display>$this->display</display>
    <options>$optionsMarkUp</options>
    <default_value>$this->defaultValue</default_value>
</element>
EOT;
    }
}
