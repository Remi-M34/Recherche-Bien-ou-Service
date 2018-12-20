<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 28/10/2018
 * Time: 19:27
 */

// énumération des types de notifications
abstract class type extends enum
{
    const notice_success = 0;
    const notice_info = 1;
    const notice_warning = 2;
    const notice_danger = 3;
}