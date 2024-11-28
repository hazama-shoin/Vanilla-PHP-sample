<?php

namespace App\Models;

use App\Utilities\Logger;
use Exception;
use PDO;
use PDOException;

class Model
{
    protected Logger $logger;
    protected PDO $pdo;
    protected string $tableName;

    public function __construct()
    {
        try {
            // ロガー初期化
            $this->logger = Logger::getInstance();

            // データベース設定読み込み
            $dbHost = $_ENV['DB_HOST'];
            $dbName = $_ENV['DB_NAME'];
            $dbUser = $_ENV['DB_USER'];
            $dbPassword = $_ENV['DB_PASSWORD'];

            // データベースに接続
            $this->pdo = new PDO(
                "mysql:dbname={$dbName};host={$dbHost};charset=utf8mb4",
                $dbUser,
                $dbPassword,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            http_response_code(500);
        }
    }

    /**
     * テーブルからデータを取得
     *
     * @param array $andWheres AndWhere検索条件。[['カラム名' => 'keyword' => '検索条件', 'is_partial' => true or false ], ...] の形の連想配列。is_partialがtrueの場合は部分一致で検索
     * @param int $offset 取得ページ（0から始まる）
     * @param int $limit 取得件数上限
     * @return array|bool データの取得に成功した場合、取得結果を配列で返却。取得失敗した場合はfalseを返却
     */
    protected function select(array $andWheres, int $offset = 0, int $limit = 10): array|bool
    {
        if (empty($this->tableName)) {
            return false;
        }

        try {
            $sql = "SELECT *, (SELECT COUNT(*) FROM {$this->tableName}) AS total_count FROM {$this->tableName}";

            // 検索条件
            $whereStr = '';
            $placeholders = [];
            foreach ($andWheres as $columnName => $where) {
                $keywords = is_array($where['keyword']) ? $where['keyword'] : [$where['keyword']];
                $isPartial = $where['is_partial'] ?? false;

                // プレースホルダ用パラメータ生成
                if (empty($keywords)) {
                    continue;
                }

                foreach ($keywords as $keyword) {
                    if ($isPartial) {
                        $keyword = "%{$keyword}%";
                    }
                    array_push(
                        $placeholders,
                        $this->placeholders($columnName, $keyword),
                    );

                    // where句生成
                    if (empty($whereStr)) {
                        $whereStr .=
                            $isPartial ? "{$columnName} LIKE :{$columnName}" : "{$columnName} = :{$columnName}";
                        continue;
                    }
                    $whereStr .=
                        $isPartial ? " AND {$columnName} LIKE :{$columnName}" : " AND {$columnName} = :{$columnName}";
                }
            }
            $sql = "SELECT *, (SELECT COUNT(*) FROM {$this->tableName}) AS total_count FROM {$this->tableName}"
                . ' LIMIT :limit OFFSET :offset';
            if (!empty($whereStr)) {
                $sql = 'SELECT *,'
                        . " (SELECT COUNT(*) FROM {$this->tableName} WHERE {$whereStr}) AS total_count"
                        . " FROM {$this->tableName} WHERE {$whereStr} LIMIT :limit OFFSET :offset";
            }
            $statement = $this->pdo->prepare($sql);

            // 値をバインド
            foreach ($placeholders as $attribute) {
                $paramType = ($attribute['type'] === 'string') ? PDO::PARAM_STR : PDO::PARAM_INT;
                $statement->bindValue($attribute['placeholder'], $attribute['value'], $paramType);
            }
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            if (!$statement->execute()) {
                return false;
            }
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * テーブルにデータを挿入
     *
     * @param array $parameters 挿入データ。[['カラム名' => '値' ], ['カラム名', '値'], ...] の形の連想配列
     * @return bool データ挿入の成否
     */
    protected function insert(array $parameters): bool
    {
        if (empty($this->tableName)) {
            return false;
        }

        try {
            $columns = [];
            $placeholders = [];

            // パラメータのカラム名とパラメータ名をバインド用に整理
            foreach ($parameters as $columnName => $value) {
                array_push($columns, $columnName);
                array_push(
                    $placeholders,
                    $this->placeholders($columnName, $value),
                );
            }

            // SQL生成
            $sql =
                "INSERT INTO {$this->tableName} (" . implode(',', $columns) . ') ' .
                'VALUES (' . implode(',', array_column($placeholders, 'placeholder')) . ')';

            $statement = $this->pdo->prepare($sql);

            // 値をバインド
            foreach ($placeholders as $attribute) {
                $paramType = ($attribute['type'] === 'string') ? PDO::PARAM_STR : PDO::PARAM_INT;
                $statement->bindValue($attribute['placeholder'], $attribute['value'], $paramType);
            }

            return $statement->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * 既存データを更新
     *
     * @param array $parameters 更新データ。[['カラム名' => '値' ], ['カラム名', '値'], ...] の形の連想配列
     * @param array $andWheres AndWhere検索条件。[['カラム名' => 'keyword' => '検索条件', 'is_partial' => true or false ], ...] の形の連想配列。is_partialがtrueの場合は部分一致で検索
     * @return bool データ更新の成否
     */
    protected function updateBase(array $parameters, array $andWheres): bool
    {
        if (empty($this->tableName)) {
            return false;
        }

        try {
            $columns = [];
            $placeholders = [];

            // パラメータのカラム名とパラメータ名をバインド用に整理
            foreach ($parameters as $columnName => $value) {
                array_push($columns, $columnName);
                array_push(
                    $placeholders,
                    $this->placeholders($columnName, $value),
                );
            }

            // SQL生成
            $sql = "UPDATE {$this->tableName} SET updated_at = :updated_at";
            $setParamStr = '';
            foreach ($columns as $column) {
                $setParamStr .= ", {$column} = :{$column}";
            }
            $sql .= $setParamStr;

            // 検索条件
            $whereStr = '';
            foreach ($andWheres as $columnName => $where) {
                $keyword = $where['keyword'] ?? '';
                $isPartial = $where['is_partial'] ?? false;

                // プレースホルダ用パラメータ生成
                if (empty($keyword)) {
                    continue;
                }
                if ($isPartial) {
                    $keyword = "%{$keyword}%";
                }
                array_push(
                    $placeholders,
                    $this->placeholders($columnName, $keyword),
                );

                // where句生成
                if (empty($whereStr)) {
                    $whereStr .= "{$columnName} = :{$columnName}";
                    continue;
                }
                $whereStr .= " AND {$columnName} = :{$columnName}";
            }
            if (!empty($whereStr)) {
                $sql .= " WHERE {$whereStr}";
            }

            $statement = $this->pdo->prepare($sql);

            // 値をバインド
            foreach ($placeholders as $attribute) {
                $paramType = ($attribute['type'] === 'string') ? PDO::PARAM_STR : PDO::PARAM_INT;
                $statement->bindValue($attribute['placeholder'], $attribute['value'], $paramType);
            }
            $statement->bindValue(':updated_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

            return $statement->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * テーブルからデータを削除
     *
     * @param int $id 削除対象のレコードID
     * @return bool 削除成否
     */
    public function delete(int $id): bool
    {
        if (empty($this->tableName)) {
            return false;
        }

        try {
            $sql = "DELETE FROM {$this->tableName} WHERE id = :id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            return $statement->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * テーブルからデータを論理削除
     *
     * @param int $id 削除対象のレコードID
     * @return bool 削除成否
     */
    public function softDelete(int $id): bool
    {
        if (empty($this->tableName)) {
            return false;
        }

        $copySuccess = false;
        try {
            $sql =
                "INSERT INTO deleted_{$this->tableName} "
                . '(email, name, password, avatar_id, is_admin, created_at, updated_at, deleted_at) '
                . 'SELECT email, name, password, avatar_id, is_admin, created_at, updated_at, :deleted_at '
                . "FROM {$this->tableName} WHERE id = :id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->bindValue(':deleted_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $copySuccess = $statement->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        if (!$copySuccess) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * プレースホルダ用配列生成
     *
     * @param string $columnName
     * @param mixed $value
     * @return array
     */
    private function placeholders(string $columnName, mixed $value): array
    {
        $isString = is_string($value);
        return [
            'placeholder' => ":{$columnName}",
            'value' => $value,
            'type' => $isString ? 'string' : 'integer',
        ];
    }
}
