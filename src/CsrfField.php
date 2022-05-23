<?php
namespace Saq\Form;

use Saq\Form\Interface\FieldInterface;

class CsrfField extends FormElement implements FieldInterface
{
    /**
     * @var string
     */
    private string $salt;

    /**
     * @var string
     */
    private string $value = '';

    /**
     * @var Error[]
     */
    private array $errors = [];

    /**
     * @param string $salt
     */
    public function __construct(string $salt)
    {
        $this->salt = $salt;

        if (!array_key_exists('csrf', $_SESSION) || !array_key_exists($salt, $_SESSION['csrf']))
        {
            $_SESSION['csrf'][$salt] = '';
        }

        $this->formName = $this->getName();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'csrf';
    }

    /**
     * @inheritDoc
     */
    public function setValue(mixed $data): void
    {
        $this->value = $data;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        $token = md5(time().$this->salt);
        $_SESSION['csrf'][$this->salt] = $token;
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function getEmptyValue(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        if ($this->value == $_SESSION['csrf'][$this->salt])
        {
            return true;
        }

        $this->errors[] = new Error('invalidCsrf');
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}