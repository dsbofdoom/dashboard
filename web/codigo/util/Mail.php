<?php

class Mail
{

    public static function EnviarParaMail (string $para, string $assunto, string $msg, string $header)
    {
        return mail($para, $assunto, $msg, $header);
    }

    public static function EnviarCCMail (string $cc, string $assunto, string $msg)
    {
        return mail("", $assunto, $msg, "CC: " . $cc);
    }

    public static function EnviarFromCCMail (string $cc, string $assunto, string $msg)
    {
        return mail("", $assunto, $msg, "From: webmaster@example.com" . "\r\nCC: " . $cc);
    }
}