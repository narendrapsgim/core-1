<?php

return [
    'supported_attributes' => [
        'attribute_name'   => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'attribute_value' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'callback_value' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'context' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'ei_type' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'email' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'external_id' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'first_name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'full_name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'id' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'last_name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'age' => \OpenDialogAi\Core\Attribute\IntAttribute::class,
        'name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'operation' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'timestamp' => \OpenDialogAi\Core\Attribute\IntAttribute::class,
        'last_seen' => OpenDialogAi\Core\Attribute\TimestampAttribute::class,
        'first_seen' => OpenDialogAi\Core\Attribute\TimestampAttribute::class,
        'attributes' => \OpenDialogAi\Core\Attribute\ArrayAttribute::class,
        'parameters' => \OpenDialogAi\Core\Attribute\ArrayAttribute::class,

        'qna_answer' => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        'current_conversation' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'current_scene' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'current_intent' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'interpreted_intent' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'next_intents' => \OpenDialogAi\Core\Attribute\ArrayAttribute::class,

        \OpenDialogAi\Core\Conversation\Model::CONVERSATION_STATUS => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        \OpenDialogAi\Core\Conversation\Model::CONVERSATION_VERSION => \OpenDialogAi\Core\Attribute\IntAttribute::class,

        \OpenDialogAi\Core\Conversation\Model::USER_ATTRIBUTE_TYPE => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        \OpenDialogAi\Core\Conversation\Model::USER_ATTRIBUTE_VALUE => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        // Intents
        \OpenDialogAi\Core\Conversation\Model::ORDER => \OpenDialogAi\Core\Attribute\IntAttribute::class,
        \OpenDialogAi\Core\Conversation\Model::CONFIDENCE => \OpenDialogAi\Core\Attribute\FloatAttribute::class,
        \OpenDialogAi\Core\Conversation\Model::COMPLETES => \OpenDialogAi\Core\Attribute\BooleanAttribute::class
    ],
];
