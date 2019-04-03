<?php


namespace OpenDialogAi\ContextEngine\ContextManager;


use Ds\Map;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Attribute\AttributeInterface;

class ContextService
{

    /* @var Map $activeContexts - a container for contexts that the service is managing */
    private $activeContexts;

    public function __construct()
    {
        $this->activeContexts = new Map();
    }

    /**
     * @param string $contextId
     * @return ContextInterface
     */
    public function createContext(string $contextId): ContextInterface
    {
        $newContext =  new BaseContext($contextId);
        $this->addContext($newContext);
        return $newContext;
    }

    /**
     * @param ContextInterface $context
     */
    public function addContext(ContextInterface $context)
    {
        $this->activeContexts->put($context->getId(), $context);
    }

    /**
     * @param string $contextId
     * @return ContextInterface
     */
    public function getContext(string $contextId)
    {
        if ($this->hasContext($contextId)) {
            return $this->activeContexts->get($contextId);
        }

        Log::debug(sprintf("Cannot get context with name %s - does not exist", $contextId));
        throw new ContextDoesNotExistException();
    }

    /**
     * @param string $contextId
     * @return bool
     */
    public function hasContext(string $contextId)
    {
        return $this->activeContexts->hasKey($contextId);
    }

    /**
     * @param string $attributeId
     * @return \OpenDialogAi\Core\Attribute\AttributeInterface
     */
    public function getAttribute(string $attributeId): AttributeInterface
    {
        /* @var ContextInterface $context */
        $context = $this->getContext($this->getContextForAttribute($attributeId));

        return $context->getAttribute($attributeId);
    }

    /**
     * @param string $attributeId
     * @return string
     */
    private function getContextForAttribute(string $attributeId): string
    {
        $contents = explode('.', $attributeId);
        return $contents[0];
    }

    /**
     * @param $userId
     */
    public function createUserContext($userId)
    {
        // @todo
    }
}
