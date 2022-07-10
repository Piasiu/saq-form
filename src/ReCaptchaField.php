<?php
namespace Saq\Form;

use ReCaptcha\ReCaptcha;
use Saq\Form\Interface\FieldInterface;

class ReCaptchaField extends FormElement implements FieldInterface
{
    /**
     * @var string
     */
    private string $value = '';

    /**
     * @var Error[]
     */
    private array $errors = [];

    /**
     * @var string
     */
    private string $hostname;

    /**
     * @var ReCaptcha
     */
    private ReCaptcha $reCaptcha;

    /**
     * @param string $secretKey
     * @param string $hostname
     * @param string $action
     */
    public function __construct(string $secretKey, string $hostname, string $action = 'submit')
    {
        $this->formName = $this->getName();
        $this->hostname = $hostname;
        $this->reCaptcha = new ReCaptcha($secretKey);
        $this->reCaptcha->setExpectedHostname($hostname)
            ->setExpectedAction($action)
            ->setScoreThreshold(0.5);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'reCaptcha';
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
        return $this->value;
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
        $response = $this->reCaptcha->verify($this->value, $this->hostname);

        if ($response->isSuccess())
        {
            return true;
        }

        $this->errors[] = new Error('invalidReCaptcha');
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