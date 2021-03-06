<?php

namespace InterpreterEngine\tests;

use GuzzleHttp\Client;
use OpenDialogAi\Core\Tests\TestCase;
use OpenDialogAi\InterpreterEngine\QnA\QnAClient;
use OpenDialogAi\InterpreterEngine\QnA\QnAResponse;

class QnAClientTest extends TestCase
{
    public function testQnAConfig()
    {
        $config = [
            'app_url' => 'app_url',
            'endpoint_key' => 'endpoint_key',
        ];

        $this->setConfigValue('opendialog.interpreter_engine.qna_config', $config);

        $client = $this->app->make(QnAClient::class);

        $this->assertEquals(QnAClient::class, get_class($client));
    }

    public function testQnAResponse()
    {
        $qnaResponse = <<<EOT
{
  "answers": [
    {
      "questions": [
        "Who created you?",
        "Where did you come from?",
        "Who made you?",
        "Who is your creator?",
        "Which people made you?",
        "Who owns you?"
      ],
      "answer": "People created me.",
      "score": 100,
      "id": 8,
      "source": "qna_chitchat_the_professional.tsv",
      "metadata": [
        {
          "name": "editorial",
          "value": "chitchat"
        }
      ],
      "context": {
        "isContextOnly": false,
        "prompts": []
      }
    }
  ],
  "debugInfo": null
}
EOT;
        $response = new QnAResponse(json_decode($qnaResponse));

        $this->assertCount(1, $response->getAnswers());
    }
}
