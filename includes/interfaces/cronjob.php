<?php

interface I_Cronjob
{
    /**
     * Metoda wywoływana na początku cronjoba.
     */
    public static function cronjob_pre();

    /**
     * Metoda wywoływana na koniec cronjoba.
     */
    public static function cronjob_post();
}
