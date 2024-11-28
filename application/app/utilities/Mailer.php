<?php

namespace App\Utilities;

use Exception;
use App\Utilities\Logger;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private Logger $logger;
    private PHPMailer $mailer;
    private static $singleton;

    /**
     * インスタンスを生成する
     */
    public static function getInstance(): mixed
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new Mailer();
        }
        return self::$singleton;
    }

    /**
     * コンストラクタ
     */
    private function __construct()
    {
        // ロガー初期化
        $this->logger = Logger::getInstance();

        // PHPMailer初期化
        $this->mailer = new PHPMailer(true);
        $this->mailer->CharSet = 'utf-8';
        $this->mailer->isHTML(true);
        if (isset($_ENV['MAIL_MAILER']) && $_ENV['MAIL_MAILER'] === 'smtp') {
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'localhost';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
            $this->mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
            $this->mailer->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? false;
            $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 1025;
        }
    }

    /**
     * メール送信
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param ?string $from
     */
    public function sendMail(string $to, string $subject, string $body, ?string $from = null): bool
    {
        try {
            $fromAddress = empty($from) ? 'notify@hazama-shoin.com' : $from;
            $this->mailer->setFrom($fromAddress);
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            return $this->mailer->send();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }
}
