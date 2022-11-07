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
        $this->setSalt($salt);
        $this->formName = $this->getName();
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;

        if (!array_key_exists('csrf', $_SESSION)
            || !array_key_exists($salt, $_SESSION['csrf'])
            || strlen($_SESSION['csrf'][$salt]) !== 32)
        {
            $this->generateToken();
        }
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
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
        return $_SESSION['csrf'][$this->salt];
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
    public function setRequired(bool $required): self
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isTransparent(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        $token = $_SESSION['csrf'][$this->salt];
        $this->generateToken();

        if ($this->value == $token)
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

    private function generateToken(): void
    {
        $_SESSION['csrf'][$this->salt] =  md5(time().$this->salt);
    }
}