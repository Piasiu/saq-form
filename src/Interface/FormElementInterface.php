<?php
namespace Saq\Form\Interface;

use Saq\Form\Form;
use Saq\Form\MultiField;
use Saq\Form\MultiForm;

interface FormElementInterface
{
    /**
     * @return string
     */
    public function getFormName(): string;

    /**
     * @param MultiForm|Form|MultiField $parent
     */
    public function setParent(MultiForm|Form|MultiField $parent): void;

    /**
     * @return Form|MultiForm|MultiField|null
     */
    public function getParent(): Form|MultiForm|MultiField|null;
}