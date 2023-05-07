<?php

namespace Nerbiz\WordClass\InputFields;

class DescriptionText extends AbstractInputField
{
    /**
     * @param string      $title
     * @param string|null $description
     */
    public function __construct(string $title = '', ?string $description = null)
    {
        $this->setFullWidth(true);

        parent::__construct('', $title, $description);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderField(): string
    {
        return '';
    }
}
