<?php

namespace App\Mixins\BulkImports;


interface IImportChannel
{

    public function import(array $items, $locale = null, $currency = null);


    public function getValidatorRule(): array;


}
