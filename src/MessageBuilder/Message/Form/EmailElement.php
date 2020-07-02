<?php

namespace OpenDialogAi\MessageBuilder\Message\Form;

class EmailElement extends BaseElement
{
    public $name;
    public $display;
    public $required;
    public $defaultValue;

    /**
     * EmailElement constructor.
     * @param $name
     * @param $display
     * @param $required
     * @param $defaultValue
     */
    public function __construct($name, $display, $required, $defaultValue = '')
    {
        $this->name = $name;
        $this->display = $display;
        $this->required = ($required) ? 'true' : 'false';
        $this->defaultValue = $defaultValue;
    }

    public function getMarkUp()
    {
        return <<<EOT
<element required="$this->required">
    <element_type>email</element_type>
    <name>$this->name</name>
    <display>$this->display</display>
    <default_value>$this->defaultValue</default_value>
</element>
EOT;
    }
}
