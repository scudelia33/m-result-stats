<?php

namespace App\Enums;

/**
 * チェックボックスのリクエストの送信値の定義
 */
enum CheckBox: string
{
    /**
     * チェックON
     */
    case ON = '1';
    /**
     * チェックOFF
     */
    case OFF = '0';
}
