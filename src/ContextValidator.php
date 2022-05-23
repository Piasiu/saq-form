<?php
namespace Saq\Form;

use JetBrains\PhpStorm\Pure;
use Saq\Form\Interface\ContextInterface;

abstract class ContextValidator extends Validator
{
    /**
     * @var ContextInterface
     */
    private ContextInterface $context;

    /**
     * @param ContextInterface $context
     */
    #[Pure]
    public function __construct(ContextInterface $context)
    {
        parent::__construct();
        $this->context = $context;
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }
}