<?php

namespace App\Utilities;

use DateTime;

/**
 * ログ
 */
class Logger
{
    // ログレベル
    private const LOG_LEVEL_ERROR = 0;
    private const LOG_LEVEL_WARN = 1;
    private const LOG_LEVEL_INFO = 2;
    private const LOG_LEVEL_DEBUG = 3;

    // ログ設定
    private bool $isLogging;
    private int $logLevel;
    private int $logMaxsize;
    private int $logPeriod;

    private static $singleton;

    /**
     * インスタンスを生成する
     */
    public static function getInstance(): mixed
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new Logger();
        }
        return self::$singleton;
    }

    /**
     * コンストラクタ
     */
    private function __construct()
    {
        $isLoggingStr = $_ENV['IS_LOGGING'] ?? 'false';
        $this->isLogging = ($isLoggingStr === 'true') ? true : false;

        $logLevel = $_ENV['LOG_LEVEL'] ?? 0;
        $this->logLevel = is_numeric($logLevel) ? $logLevel : 0;

        $logMaxsize = $_ENV['LOG_MAXSIZE'] ?? 10485760;
        $this->logMaxsize = is_numeric($logMaxsize) ? $logMaxsize : 10485760;

        $logPeriod = $_ENV['LOG_PERIOD'] ?? 30;
        $this->logPeriod = is_numeric($logPeriod) ? $logPeriod : 30;
    }

    /**
     * ERRORレベルのログ出力する
     * @param string $message メッセージ
     */
    public function error(string $message): void
    {
        if (self::LOG_LEVEL_ERROR <= $this->logLevel) {
            $this->output('ERROR', $message);
        }
    }

    /**
     * WARNレベルのログ出力する
     * @param string $message メッセージ
     */
    public function warn(string $message): void
    {
        if (self::LOG_LEVEL_WARN <= $this->logLevel) {
            $this->output('WARN', $message);
        }
    }

    /**
     * INFOレベルのログ出力する
     * @param string $message メッセージ
     */
    public function info(string $message): void
    {
        if (self::LOG_LEVEL_INFO <= $this->logLevel) {
            $this->output('INFO', $message);
        }
    }

    /**
     * DEBUGレベルのログ出力する
     * @param string $message メッセージ
     */
    public function debug(string $message): void
    {
        if (self::LOG_LEVEL_DEBUG <= $this->logLevel) {
            $this->output('DEBUG', $message);
        }
    }

    /**
     * ログ出力する
     * @param string $level ログレベル
     * @param string $message メッセージ
     */
    private function output(string $level, string $message): void
    {
        if ($this->isLogging) {
            $pid = getmypid();
            $time = $this->getTime();
            $logMessage = "[{$time}][{$pid}][{$level}] " . rtrim($message) . "\n";
            $logFileDir = '../storage/logs/';
            $logFileName = strtolower($level);
            $logFilePath = "{$logFileDir}{$logFileName}.log";

            $result = file_put_contents($logFilePath, $logMessage, FILE_APPEND | LOCK_EX);
            if (!$result) {
                error_log('LogUtil::output error_log ERROR', 0);
                return;
            }

            if ($this->logMaxsize < filesize($logFilePath)) {
                // ファイルサイズを超えた場合、リネームしてgz圧縮する
                $rotateDate = date('YmdHis');
                $rotateLogFilePath = "{$logFileDir}{$logFileName}_{$rotateDate}.log";
                rename($logFilePath, $rotateLogFilePath);
                $gz = gzopen($rotateLogFilePath . '.gz', 'w9');
                if ($gz) {
                    gzwrite($gz, file_get_contents($rotateLogFilePath));
                    $isClose = gzclose($gz);
                    if ($isClose) {
                        unlink($rotateLogFilePath);
                    } else {
                        error_log("gzclose ERROR.", 0);
                        return;
                    }
                } else {
                    error_log("gzopen ERROR.", 0);
                    return;
                }

                // 古いログファイルを削除する
                $retentionDate = new DateTime();
                $retentionDate->modify("-{$this->logPeriod} day");
                if ($dh = opendir($logFileDir)) {
                    while (($fileName = readdir($dh)) !== false) {
                        $pm = preg_match("/" . preg_quote($logFileName) . "_(\d{14}).*\.gz/", $fileName, $matches);
                        if ($pm === 1) {
                            $logCreatedDate = DateTime::createFromFormat('YmdHis', $matches[1]);
                            if ($logCreatedDate < $retentionDate) {
                                unlink("{$logFileDir}{$fileName}");
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }
    }

    /**
     * 現在時刻を取得する
     * @return string 現在時刻
     */
    private function getTime(): string
    {
        $miTime = explode('.', microtime(true));
        $msec = str_pad(substr($miTime[1], 0, 3), 3, "0");
        $time = date('Y-m-d H:i:s', $miTime[0]) . '.' . $msec;
        return $time;
    }
}
