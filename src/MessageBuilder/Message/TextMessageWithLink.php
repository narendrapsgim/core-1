<?php

namespace OpenDialogAi\MessageBuilder\Message;

class TextMessageWithLink
{
    public $text;

    public $linkText;

    public $linkUrl;

    /**
     * TextMessageWithLink constructor.
     * @param $text
     * @param $linkText
     * @param $linkUrl
     * @param bool $linkNewTab
     */
    public function __construct($text, $linkText, $linkUrl, $linkNewTab)
    {
        $this->text = $text;
        $this->linkText = $linkText;
        $this->linkUrl = $linkUrl;
        $this->linkNewTab = ($linkNewTab) ? 'true' : 'false';
    }

    public function getMarkUp()
    {
        return <<<EOT
<text-message>{$this->text} <link new_tab="$this->linkNewTab">
<url>{$this->linkUrl}</url><text>{$this->linkText}</text></link></text-message>
EOT;
    }
}
