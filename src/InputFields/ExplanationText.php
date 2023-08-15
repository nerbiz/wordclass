<?php

namespace Nerbiz\WordClass\InputFields;

class ExplanationText extends AbstractInputField
{
    /**
     * @param string $title
     */
    public function __construct(string $title = '')
    {
        $this->setFullWidth(true);

        parent::__construct('', $title);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderField(): string
    {
        return '';
    }
}
