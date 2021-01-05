<?php

class Verify
{
    protected function requiredFieldsVerification()
    {
        foreach ($_POST as $current) {
            if (mb_strlen($current) == 0) {
                echo "Все поля обязательны для заполнения. </br> Пожалуйста, вернитесь назад и заполните все поля. </br>";
                exit;
            }
        }
    }

    ///////////////////////////////////

    protected function verifyNumber($text)
    {
        // Проверяем поэлементно номер категории: он должен состоять только из цифр
        for ($i = 0; $i < mb_strlen($text); $i++) {
            if (preg_match('/^[0-9]+$/', mb_substr($text, $i, 1)) === 0) {
                echo "Введённые данные не соответствуют корректному формату. </br> Пожалуйста, вернитесь назад и введите данные снова. </br>";
                exit;
            }
        }
    }
}  
