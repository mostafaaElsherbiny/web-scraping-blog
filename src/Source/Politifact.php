<?php

namespace App\Source;

use App\Scraper\Contracts\SourceInterface;

class Politifact implements SourceInterface
{
    public function getUrl(): string
    {
        return 'https://www.politifact.com/factchecks/list';
    }

    public function getName(): string
    {
        return 'Politifact';
    }

    public function getWrapperSelector(): string
    {
        return '.o-listicle__item';
    }

    public function getTitleSelector(): string
    {
        return '.m-statement__name';
    }

    public function getBodySelector(): string
    {
        return '.m-statement__quote';
    }

    public function getDateSelector(): string
    {
        return '.m-statement__desc';
    }


    public function getImageSelector(): string
    {
        return '.c-image__thumb';
    }
}
