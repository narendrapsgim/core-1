<?php

namespace OpenDialogAi\MessageBuilder\Message\Button;

class TranscriptDownloadButton extends BaseButton
{
    public $text;

    public $display;

    public $type;

    /**
     * TabSwitchButton constructor.
     * @param $text
     * @param bool $display
     * @param string $type
     */
    public function __construct($text, $display = true, $type = "")
    {
        $this->text = $text;
        $this->display = ($display) ? 'true' : 'false';
        $this->type = $type;
    }

    public function getMarkUp()
    {
        $typeProperty = $this->type != "" ? " type='$this->type'" : "";

        return <<<EOT
<button$typeProperty display="$this->display">
    <text>$this->text</text>
    <download>true</download>
</button>
EOT;
    }
}
