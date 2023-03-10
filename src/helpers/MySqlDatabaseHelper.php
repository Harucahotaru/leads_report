<?php

declare(strict_types=1);

namespace app\helpers;

use mysqli;
use app\exceptions\DatabaseException;

class MySqlDatabaseHelper
{
    const DATABASE_ADDRESS = '10.10.101.25';

    const DATABASE_USERNAME = 'fouser1';

    const DATABASE_PASSWORD = 'QNpXxCSBdMMRivne';

    const DATABASE_NAME = 'frontoffice1';

    public mysqli $dbConnect;

    /**
     * @throws DatabaseException
     */
    public function __construct()
    {
        $this->dbConnect = $this->connect();
    }

    /**
     * @return mysqli
     *
     * @throws databaseException
     */
    public function connect(): mysqli
    {
        $connect = new mysqli(
            self::DATABASE_ADDRESS,
            self::DATABASE_USERNAME,
            self::DATABASE_PASSWORD,
            self::DATABASE_NAME
        );

        mysqli_set_charset($connect, "utf8mb4");

        if ($connect === false) {
            throw new databaseException('Не удалось подключиться к бд,' . mysqli_connect_error());
        }

        return $connect;
    }

    /**
     * @param string $query
     *
     * @return array
     *
     * @throws databaseException
     */
    public function executeQuery(string $query): array
    {
        $result = [];

        $mysqliResult = $this->dbConnect->query($query);

        if ($mysqliResult === false) {
            throw new DatabaseException('Не удалось выполнить запрос к базе - ' . $this->dbConnect->error);
        }

        if ($mysqliResult === true) {
            return $mysqliResult;
        }

        while ($obj = $mysqliResult->fetch_object()) {
            if (!empty($obj)) {
                $result[] = $obj;
            }
        }
        if (!is_array($result)) {
            $result = array($result);
        }

        return $result;
    }
}