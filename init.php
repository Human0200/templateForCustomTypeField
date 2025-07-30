<?php
// /local/php_interface/init.php

class AreaCoefficientProperty
{
    public static function GetUserTypeDescription()
    {
        return [
            'PROPERTY_TYPE' => 'S', // Строковый тип
            'USER_TYPE' => 'area_coefficient', // Уникальный идентификатор
            'DESCRIPTION' => 'Площадь и коэффициент',

            // Основные методы
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],

            // Для отображения в списке
            'GetAdminFilterHTML' => [__CLASS__, 'GetAdminFilterHTML'],
            'GetAdminListViewHTML' => [__CLASS__, 'GetAdminListViewHTML'],
            'GetPublicViewHTML' => [__CLASS__, 'GetPublicViewHTML'],
        ];
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $values = self::ParseValue($value['VALUE']);

        return '
        <div style="margin:10px 0;">
            <div style="margin-bottom:5px;">
                <span style="display:inline-block;width:120px;">Площадь (м²):</span>
                <input type="text" 
                       name="'.$strHTMLControlName['VALUE'].'[area]" 
                       value="'.htmlspecialcharsbx($values['area']).'">
            </div>
            <div>
                <span style="display:inline-block;width:120px;">Коэффициент:</span>
                <input type="text" 
                       name="'.$strHTMLControlName['VALUE'].'[coeff]" 
                       value="'.htmlspecialcharsbx($values['coeff']).'">
            </div>
        </div>';
    }

    public static function ConvertToDB($arProperty, $value)
    {
        if (is_array($value['VALUE'])) {
            $value['VALUE'] = serialize($value['VALUE']);
        }
        return $value;
    }

    public static function ConvertFromDB($arProperty, $value)
    {
        if (!empty($value['VALUE'])) {
            $value['VALUE'] = unserialize($value['VALUE']);
        }
        return $value;
    }

    public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        $values = self::ParseValue($value['VALUE']);
        return $values['area'].' / '.$values['coeff'];
    }

    protected static function ParseValue($value)
    {
        if (is_array($value)) {
            return [
                'area' => $value['area'] ?? '',
                'coeff' => $value['coeff'] ?? ''
            ];
        }

        if (is_string($value) && !empty($value)) {
            $unserialized = @unserialize($value);
            if ($unserialized !== false) {
                return $unserialized;
            }
        }

        return ['area' => '', 'coeff' => ''];
    }
}

// Регистрация через правильное событие
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', function() {
    return AreaCoefficientProperty::GetUserTypeDescription();
});