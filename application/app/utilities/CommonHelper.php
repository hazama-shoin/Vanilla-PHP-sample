<?php

namespace App\Utilities;

use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * ヘルパ
 */
class CommonHelper
{
    /**
     * 対象ディレクトリ内のファイル一覧を再帰的探索
     *
     * @param string $targetDirectory 対象ディレクトリ
     * @return array ファイル一覧
     */
    public static function getFilesRecursive(string $targetDirectory): array
    {
        $fileList = [];

        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $targetDirectory,
                    FilesystemIterator::SKIP_DOTS
                    | FilesystemIterator::KEY_AS_PATHNAME
                    | FilesystemIterator::CURRENT_AS_FILEINFO
                ),
                RecursiveIteratorIterator::LEAVES_ONLY // ファイルだけ取得
            ) as $item
        ) {
            if ($item->isFile()) {
                array_push($fileList, $item->getPathname());
            }
        }

        return $fileList;
    }

    /**
     * base64文字列が有効な画像か確認
     *
     * @param string $base64
     * @param int $maxWidth
     * @param int $maxHeight
     * @param array $validImageTypes
     * @return bool
     */
    public static function checkBase64IsValidImage(
        string $base64,
        int $maxWidth = 500,
        int $maxHeight = 500,
        array $validImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG]
    ): bool {
        $imageInfo = getimagesize("data:application/octet-stream;base64,{$base64}");
        if (!$imageInfo) {
            return false;
        }
        list($width, $height, $type,) = $imageInfo;
        if ($width > $maxWidth || $height > $maxHeight) {
            return false;
        }
        if (!in_array($type, $validImageTypes)) {
            return false;
        }
        return true;
    }

    /**
     * base64文字列を画像に変換して保存
     *
     * @param string $base64
     * @param string $outputPath
     * @return ?string 保存したファイルのID（ファイル名）を返却。保存に失敗した場合はfalseを返す
     */
    public static function base64ToImage(
        string $base64,
        string $outputPath = __DIR__ . '/../../storage/images/'
    ): string|bool {
        try {
            if (!CommonHelper::checkBase64IsValidImage($base64, 500, 500, [IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                return false;
            }

            $fileId = uniqid();
            $image = base64_decode($base64);

            if (!file_put_contents("{$outputPath}{$fileId}", $image)) {
                return false;
            }
            return $fileId;
        } catch (Exception $e) {
            $logger = Logger::getInstance();
            $logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * ファイルIDに対応する画像を取得
     *
     * @param string $fileId
     * @param string $inputPath
     * @return string|bool
     */
    public static function getImageToBase64(
        string $fileId,
        string $inputPath = __DIR__ . '/../../storage/images/'
    ): string|bool {
        try {
            $imagePath = "{$inputPath}{$fileId}";
            $image = false;
            if (!empty($fileId) && file_exists($imagePath)) {
                $image = file_get_contents("{$inputPath}{$fileId}");
            } else {
                $image = file_get_contents("{$inputPath}noimage.jpg");
            }
            $base64 = base64_encode($image);
            $imageInfo = getimagesize("data:application/octet-stream;base64,{$base64}");
            if (!$imageInfo) {
                return false;
            }
            return "data:{$imageInfo['mime']};base64,{$base64}";
        } catch (Exception $e) {
            $logger = Logger::getInstance();
            $logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * 配列を元に16進数文字列を生成
     *
     * @param array $value
     * @return string|false
     */
    public static function encodeHexString(array $value): string|false
    {
        $key = $_ENV['APP_TOKEN_ENCRYPT_KEY'] ?? '';
        $iv = $_ENV['APP_TOKEN_ENCRYPT_IV'] ?? '';
        $json = json_encode($value);
        if (!$json) {
            return false;
        }
        return bin2hex(openssl_encrypt($json, 'AES-256-CBC', $key, 0, $iv));
    }

    /**
     * encodeHexStringで生成した16進数文字列を配列に戻す
     *
     * @param string $value
     * @return array|false
     */
    public static function decodeHexString(string $value): array|false
    {
        $key = $_ENV['APP_TOKEN_ENCRYPT_KEY'] ?? '';
        $iv = $_ENV['APP_TOKEN_ENCRYPT_IV'] ?? '';
        $json = openssl_decrypt(hex2bin($value), 'AES-256-CBC', $key, 0, $iv);
        return json_decode($json, true) ?? false;
    }
}
