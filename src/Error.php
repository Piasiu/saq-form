<?php
namespace Saq\Form;

class Error
{
    public const IS_REQUIRED = 'fieldIsRequired';

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string[]
     */
    private array $arguments;

    public function __construct(string $name, array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

}