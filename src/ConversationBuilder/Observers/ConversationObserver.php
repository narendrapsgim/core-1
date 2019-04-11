<?php

namespace OpenDialogAi\ConversationBuilder\Observers;

use OpenDialogAi\ConversationBuilder\Conversation;
use OpenDialogAi\ConversationBuilder\ConversationStateLog;
use Spatie\Activitylog\Models\Activity;

class ConversationObserver
{
    /**
     * Handle the conversation "created" event.
     *
     * @param  \OpenDialogAiConversationBuilder\Conversation  $conversation
     * @return void
     */
    public function created(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the conversation "updated" event.
     *
     * @param  \OpenDialogAiConversationBuilder\Conversation  $conversation
     * @return void
     */
    public function updated(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the conversation "deleted" event.
     *
     * @param  \OpenDialogAiConversationBuilder\Conversation  $conversation
     * @return void
     */
    public function deleted(Conversation $conversation)
    {
        // Unpublish the conversation from DGraph.
        $conversation->unPublishConversation(false);

        // Remove related state logs.
        ConversationStateLog::where('conversation_id', $conversation->id)->delete();

        // Remove related activity logs.
        Activity::where('subject_id', $conversation->id)->delete();
    }

    /**
     * Handle the conversation "restored" event.
     *
     * @param  \OpenDialogAiConversationBuilder\Conversation  $conversation
     * @return void
     */
    public function restored(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the conversation "force deleted" event.
     *
     * @param  \OpenDialogAiConversationBuilder\Conversation  $conversation
     * @return void
     */
    public function forceDeleted(Conversation $conversation)
    {
        //
    }
}