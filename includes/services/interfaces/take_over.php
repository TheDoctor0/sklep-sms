<?php

/**
 * Obsługa przejmowania usług przez użytkowników
 * Interface IService_TakeOver.
 */
interface IService_TakeOver
{
    /**
     * Zwraca formularz przejęcia usługi.
     *
     * @return string
     */
    public function service_take_over_form_get();

    /**
     * Sprawdza poprawność danych wprowadzonych w formularzu przejęcia usługi
     * a jeżeli wszystko jest ok, to ją przejmuje.
     *
     * @param array $post Dane $_POST
     *
     * @return array
     *               status => id wiadomości
     *               text => treść wiadomości
     *               positive => czy udało się przejąć usługę
     */
    public function service_take_over($post);
}
