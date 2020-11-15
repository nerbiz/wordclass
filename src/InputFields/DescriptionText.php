<?php

namespace Nerbiz\Wordclass\InputFields;

class DescriptionText extends AbstractInputField
{
    /**
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(?string $title = null, ?string $description = null)
    {
        $this->fullWidth = true;

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
