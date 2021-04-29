<?php

namespace Nerbiz\WordClass\InputFields;

class DescriptionText extends AbstractInputField
{
    /**
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(?string $title = null, ?string $description = null)
    {
        $this->setFullWidth(true);

        parent::__construct('', $title ?? '', $description);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderField(): string
    {
        return '';
    }
}
