<?php

namespace App\Mixins\BulkImports;

class ImportManager
{
    /**
     * @param string $importDataType
     * @return IImportChannel
     */
    public static function makeChannel($importDataType)
    {
        $channelName = self::getImportChannelNameByDataType($importDataType);

        $className = "App\\Mixins\\BulkImports\\Drivers\\{$channelName}";
        return new $className();
    }

    private static function getImportChannelNameByDataType($importDataType):string
    {
        $name = "";

        switch ($importDataType) {
            case 'courses':
                $name = "CoursesBulkImports";
                break;
            case 'categories':
                $name = "CategoriesBulkImports";
                break;
            case 'users':
                $name = "UsersBulkImports";
                break;
            case 'products':
                $name = "ProductsBulkImports";
                break;
        }

        return $name;
    }

}
