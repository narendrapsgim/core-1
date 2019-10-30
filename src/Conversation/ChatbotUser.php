<?php

namespace OpenDialogAi\Core\Conversation;

use Ds\Map;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\ContextEngine\Exceptions\AttributeIsNotSupported;
use OpenDialogAi\Core\Attribute\AttributeDoesNotExistException;
use OpenDialogAi\Core\Attribute\AttributeInterface;
use OpenDialogAi\Core\Attribute\StringAttribute;
use OpenDialogAi\Core\Graph\Node\Node;

class ChatbotUser extends Node
{
    /** @var string Id of the current conversation */
    private $currentConversationUid;

    /** @var string */
    private $currentIntentUid;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addAttribute(new StringAttribute(Model::EI_TYPE, Model::CHATBOT_USER));
    }

    /**
     * @return bool
     */
    public function isHavingConversation() : bool
    {
        return isset($this->currentConversationUid);
    }

    /**
     * Attaches an entire conversation to the user
     *
     * @param Conversation $conversationForCloning Required to ensure that the new conversation is fully
     * cloned by `UserService.updateUser`
     * @param Conversation $conversationForConnecting Required to ensure that DGraph contains a correct `instance_of`
     * edge between template & instance
     */
    public function setCurrentConversation(Conversation $conversationForCloning, Conversation $conversationForConnecting)
    {
        $currentConversation = clone $conversationForCloning;
        $currentConversation->setConversationType(Model::CONVERSATION_USER);
        $this->createOutgoingEdge(Model::HAVING_CONVERSATION, $currentConversation);

        $currentConversation->createOutgoingEdge(Model::INSTANCE_OF, $conversationForConnecting);
    }

    /**
     * Sets just the uid of the current conversation
     *
     * @param string $currentConversationUid
     * @return ChatbotUser
     */
    public function setCurrentConversationUid(string $currentConversationUid): ChatbotUser
    {
        $this->currentConversationUid = $currentConversationUid;
        return $this;
    }

    /**
     * Returns the uid of the users current intent
     *
     * @return string
     */
    public function getCurrentConversationUid(): string
    {
        return $this->currentConversationUid;
    }

    /**
     * Sets just the uid of the current intent
     *
     * @param string $intentUid
     * @return ChatbotUser
     */
    public function setCurrentIntentUid(string $intentUid): ChatbotUser
    {
        $this->currentIntentUid = $intentUid;
        return $this;
    }

    /**
     * Returns the ID of the user's current intent
     *
     * @return string
     */
    public function getCurrentIntentUid(): string
    {
        return $this->currentIntentUid;
    }

    /**
     * Removes the current conversation and current intent IDs from the user
     * @return void
     */
    public function unsetCurrentConversation(): void
    {
        unset($this->currentIntentUid);
        unset($this->currentConversationUid);
    }

    /**
     * Checks whether the user has a current intent id
     *
     * @return bool
     */
    public function hasCurrentIntent(): bool
    {
        return isset($this->currentIntentUid);
    }

    /**
     * @param AttributeInterface $userAttribute
     * @return UserAttribute
     */
    public function addUserAttribute(AttributeInterface $userAttribute): UserAttribute
    {
        $node = new UserAttribute($userAttribute);

        $this->createOutgoingEdge(Model::HAS_ATTRIBUTE, $node);

        return $node;
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function setUserAttribute(AttributeInterface $attribute): void
    {
        try {
            /** @var UserAttribute $userAttribute */
            $userAttribute = $this->getAllUserAttributes()->get($attribute->getId(), null);

            if (is_null($userAttribute)) {
                Log::debug(sprintf("Cannot return attribute with name %s - does not exist", $attribute->getId()));
                throw new AttributeDoesNotExistException(
                    sprintf("Cannot return attribute with name %s - does not exist", $attribute->getId())
                );
            }

            $userAttribute->updateInternalAttribute($attribute);
        } catch (AttributeIsNotSupported $e) {
            Log::warning(sprintf('Trying to set unsupported attribute %s to user', $attribute->getId()));
        }
    }

    /**
     * @param $attributeName
     * @return bool
     */
    public function hasUserAttribute($attributeName): bool
    {
        return !is_null($this->getAllUserAttributes()->get($attributeName, null));
    }

    /**
     * @param string $userAttributeId
     * @return AttributeInterface
     * @throws AttributeDoesNotExistException
     */
    public function getUserAttribute(string $userAttributeId): AttributeInterface
    {
        /** @var UserAttribute $userAttribute */
        $userAttribute = $this->getAllUserAttributes()->get($userAttributeId, null);

        if (is_null($userAttribute)) {
            Log::debug(sprintf("Cannot return attribute with name %s - does not exist", $userAttributeId));
            throw new AttributeDoesNotExistException(
                sprintf("Cannot return attribute with name %s - does not exist", $userAttributeId)
            );
        }

        return $userAttribute->getInternalAttribute();
    }

    /**
     * @param string $userAttributeId
     * @return AttributeInterface|null
     * @throws AttributeDoesNotExistException
     */
    public function getUserAttributeValue(string $userAttributeId): string
    {
        return $this->getUserAttribute($userAttributeId)->getValue();
    }

    /**
     * @return Map
     */
    public function getAllUserAttributes(): Map
    {
        return $this->getNodesConnectedByOutgoingRelationship(Model::HAS_ATTRIBUTE);
    }
}
