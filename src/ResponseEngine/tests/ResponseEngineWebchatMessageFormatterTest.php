<?php

namespace OpenDialogAi\ResponseEngine\Tests;

use OpenDialogAi\Core\Tests\TestCase;
use OpenDialogAi\ResponseEngine\Message\WebChatMessageFormatter;

class ResponseEngineWebchatMessageFormatterTest extends TestCase
{
    public function testEmptyMessage()
    {
        $markup = '<message disable_text="1"><empty-message></empty-message></message>';
        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $this->assertEquals(true, $messages[0]->isEmpty());
        $this->assertEquals(1, $messages[0]->getData()['disable_text']);

        $markup = '<message disable_text="0"><empty-message></empty-message></message>';
        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $this->assertEquals(true, $messages[0]->isEmpty());
        $this->assertEquals(0, $messages[0]->getData()['disable_text']);
    }

    public function testTextMessage()
    {
        $markup = '<message disable_text="1"><text-message>hi there</text-message></message>';
        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $this->assertEquals('hi there', $messages[0]->getText());
        $this->assertEquals(1, $messages[0]->getData()['disable_text']);

        $markup = <<<EOT
<message disable_text="0">
  <text-message>
    hi there
  </text-message>
</message>
EOT;

        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $this->assertEquals('hi there', $messages[0]->getText());
        $this->assertEquals(0, $messages[0]->getData()['disable_text']);
    }

    public function testImageMessage()
    {
        $markup = '<message disable_text="1"><image-message link_new_tab="1"><link>https://www.opendialog.ai</link><src>https://www.opendialog.ai/assets/images/logo.svg</src></image-message></message>';
        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $message = $messages[0];
        $this->assertEquals('https://www.opendialog.ai', $message->getImgLink());
        $this->assertEquals('https://www.opendialog.ai/assets/images/logo.svg', $message->getImgSrc());
        $this->assertEquals(true, $message->getLinkNewTab());
        $this->assertEquals(1, $message->getData()['disable_text']);

        $markup = <<<EOT
<message disable_text="0">
  <image-message link_new_tab="0">
    <link>
      https://www.opendialog.ai
    </link>
    <src>
      https://www.opendialog.ai/assets/images/logo.svg
    </src>
  </image-message>
</message>
EOT;

        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $message = $messages[0];
        $this->assertEquals('https://www.opendialog.ai', $message->getImgLink());
        $this->assertEquals('https://www.opendialog.ai/assets/images/logo.svg', $message->getImgSrc());
        $this->assertEquals(false, $message->getLinkNewTab());
        $this->assertEquals(0, $message->getData()['disable_text']);
    }

    public function testButtonMessage()
    {
        $markup = '<message disable_text="1"><button-message clear_after_interaction="1"><button><text>Yes</text><callback>callback_yes</callback><value>true</value></button><button><text>No</text><callback>callback_no</callback><value>false</value></button></button-message></message>';
        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $message = $messages[0];

        $expectedOutput = [
            [
                'text' => 'Yes',
                'callback_id' => 'callback_yes',
                'value' => 'true',
            ],
            [
                'text' => 'No',
                'callback_id' => 'callback_no',
                'value' => 'false',
            ],
        ];

        $this->assertEquals(true, $message->getData()['clear_after_interaction']);
        $this->assertEquals(true, $message->getData()['disable_text']);
        $this->assertEquals($expectedOutput, $message->getButtonsArray());

        $markup = <<<EOT
<message disable_text="0">
  <button-message clear_after_interaction="0">
    <button>
      <text>
        Yes
      </text>
      <callback>
        callback_yes
      </callback>
      <value>
        true
      </value>
    </button>
    <button>
      <text>
        No
      </text>
      <callback>
        callback_no
      </callback>
      <value>
        false
      </value>
    </button>
  </button-message>
</message>
EOT;

        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $message = $messages[0];

        $expectedOutput = [
            [
                'text' => 'Yes',
                'callback_id' => 'callback_yes',
                'value' => 'true',
            ],
            [
                'text' => 'No',
                'callback_id' => 'callback_no',
                'value' => 'false',
            ],
        ];

        $this->assertEquals(false, $message->getData()['clear_after_interaction']);
        $this->assertEquals(false, $message->getData()['disable_text']);
        $this->assertEquals($expectedOutput, $message->getButtonsArray());
    }

    public function testListMessage()
    {
        $markup = <<<EOT
<message disable_text="0">
  <list-message view-type="vertical">
    <item>
      <button-message>
        <text>button message text</text>
        <button>
          <text>Yes</text>
        </button>
      </button-message>
    </item>
    <item>
      <image-message>
        <src>
          https://www.opendialog.ai/assets/images/logo.svg
        </src>
        <link new_tab="true">
          https://www.opendialog.ai
        </link>
      </image-message>
    </item>
    <item>
      <text-message>message-text</text-message>
    </item>
  </list-message>
</message>
EOT;

        $expectedOutput = [
            [
                'text' => 'button message text',
                'disable_text' => false,
                'internal' => false,
                'hidetime' => false,
                'buttons' => [
                    [
                        'text' => 'Yes',
                        'callback_id' => '',
                        'value' => '',
                    ],
                ],
                'clear_after_interaction' => false,
                'message_type' => 'button',
            ],
            [
                'img_src' => 'https://www.opendialog.ai/assets/images/logo.svg',
                'img_link' => 'https://www.opendialog.ai',
                'link_new_tab' => false,
                'disable_text' => false,
                'internal' => false,
                'hidetime' => false,
                'message_type' => 'image',
            ],
            [
                'text' => 'message-text',
                'disable_text' => false,
                'internal' => false,
                'hidetime' => false,
                'message_type' => 'text',
            ],
        ];

        $formatter = new WebChatMessageFormatter;
        $messages = $formatter->getMessages($markup);
        $message = $messages[0];

        $data = $message->getData();

        $this->assertEquals(false, $message->getData()['disable_text']);
        $this->assertEquals('vertical', $data['view_type']);
        $this->assertArraySubset($expectedOutput[0], $data['items'][0]);
        $this->assertArraySubset($expectedOutput[1], $data['items'][1]);
        $this->assertArraySubset($expectedOutput[2], $data['items'][2]);
    }
}
