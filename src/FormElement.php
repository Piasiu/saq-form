<?php
namespace Saq\Form;

abstract class FormElement
{
    /**
     * @var string
     */
    protected string $formName = '';

    /**
     * @var Form|MultiForm|MultiField|null
     */
    protected Form|MultiForm|MultiField|null $parent = null;

    /**
     * @return string
     */
    public function getFormName(): string
    {
        if ($this->getParent() === null || $this->getParent()->getFormName() == '')
        {
            return $this->formName;
        }

        $name = $this->getParent()->getFormName();

        if ($this->formName !== '')
        {
            $name .= '['.$this->formName.']';
        }

        return $name;
    }

    /**
     * @param MultiForm|Form|MultiField $parent
     */
    public function setParent(MultiForm|Form|MultiField $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Form|MultiForm|MultiField|null
     */
    public function getParent(): Form|MultiForm|MultiField|null
    {
        return $this->parent;
    }
}