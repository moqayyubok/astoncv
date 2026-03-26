<?php

require_once __DIR__ . '/functions.php';

class DbSessionHandler implements SessionHandlerInterface
{
    private PDO $pdo;

    public function open(string $path, string $name): bool
    {
        try {
            $this->pdo = getDB();
        } catch (PDOException $e) {
            error_log('DbSessionHandler::open() failed: ' . $e->getMessage());
            return false;
        }
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT data FROM sessions WHERE id = ? LIMIT 1'
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['data'] : '';
        } catch (PDOException $e) {
            error_log('DbSessionHandler::read() failed: ' . $e->getMessage());
            return '';
        }
    }

    public function write(string $id, string $data): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO sessions (id, data, last_accessed)
                 VALUES (?, ?, UNIX_TIMESTAMP())
                 ON DUPLICATE KEY UPDATE
                   data          = VALUES(data),
                   last_accessed = UNIX_TIMESTAMP()'
            );
            return $stmt->execute([$id, $data]);
        } catch (PDOException $e) {
            error_log('DbSessionHandler::write() failed: ' . $e->getMessage());
            return false;
        }
    }

    public function destroy(string $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM sessions WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function gc(int $max_lifetime): int|false
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM sessions WHERE last_accessed < UNIX_TIMESTAMP() - ?'
        );
        $stmt->execute([$max_lifetime]);
        return $stmt->rowCount();
    }
}
