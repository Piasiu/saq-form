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
     * @var ErrorArgument[]
     */
    private array $arguments = [];

    public function __construct(string $name, array $arguments = [])
    {
        $this->name = $name;

        foreach ($arguments as $name => $value)
        {
            $this->arguments[] = new ErrorArgument($name, $value);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ErrorArgument[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

}