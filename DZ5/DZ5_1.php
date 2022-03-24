<?php

/**
 * Решение п.1
 * Реализовать на PHP пример Декоратора, позволяющий отправлять уведомления несколькими различными способами
 */

/**
 * component interface
 */
interface Notifyer
{
    public function notify(string $message);
}

/**
 * Concrete component (базовый класс, на который будут навешиваться декораторы
 */
class SimpleNotification implements Notifyer
{
    public function notify(string $message)
    {
        return "[" . $message . "]";
    }
}

/**
 * Базовый класс декораторов
 */
class BasicNotifyer implements Notifyer
{
    protected $notifyer;

    public function __construct(Notifyer $notifyer)
    {
        $this->notifyer = $notifyer;
    }

    public function notify(string $message)
    {
        return $this->notifyer->notify($message);
    }
}

/**
 * Остается создать Concrete Decorators
 * "Наплодим" различные декораторы под "разные нужды"
 */

/**
 * EmailDecorator - для отправки сообщений по email
 */
class EmailNotifyer extends BasicNotifyer
{
    public function notify(string $message)
    {
        return "Email send: " . parent::notify($message); // TODO: Change the autogenerated stub
    }
}

/**
 * Telegram Decorator - для отправки сообщений через Telegram
 */
class TelegramNotifyer extends BasicNotifyer
{
    public function notify(string $message)
    {
        return "Telegram send: " . parent::notify($message); // TODO: Change the autogenerated stub
    }
}

/**
 * SMSDecorator - для отправки сообщений по "старинке" через SMS
 */
class SMSNotifyer extends BasicNotifyer
{
    public function notify(string $message)
    {
        return "SMS send: " . parent::notify($message); // TODO: Change the autogenerated stub
    }
}

/**
 * Осталовь реализовать клиента
 */
function send(string $message, Notifyer $decorator)
{
    echo "\n" . $decorator->notify($message);
}

/**
 * ТЕСТЫ
 * Создаеем экземпляр базового класса сообщения
 */
$baseNotification = new SimpleNotification();
/**
 * Создаем экземпляры различных декораторов
 * простых и составных
 */
$emailNotifyer = new EmailNotifyer($baseNotification);
$telegramNotifyer = new TelegramNotifyer($baseNotification);
$smsNotifyer = new SMSNotifyer($baseNotification);
$telegramAndSMSNotifyer = new TelegramNotifyer($smsNotifyer);
$emailAndSmsNotifyer = new EmailNotifyer($smsNotifyer);
$allNotifyer = new EmailNotifyer($telegramAndSMSNotifyer);
/**
 * Запускаем
 */
$message = "Отправляемое сообщение";
send($message, $emailNotifyer);
send($message, $telegramNotifyer);
send($message, $smsNotifyer);
send($message, $telegramAndSMSNotifyer);
send($message, $emailAndSmsNotifyer);
send($message, $allNotifyer);


