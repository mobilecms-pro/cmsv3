<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine;

/**
 * Класс для работы с mysql
 * @author KpuTuK <bykputuk@ya.ru>
 */
class MySQLiDB
{
    /**
     * Объект, представляющий подключение к серверу MySQL
     * @var \mysqli
     */
    protected $link = null;
    /**
     * Количество запросов вызванных с помощью MySQLiDB::query()
     * @var int
     */
    protected $queryCount = 0;
    protected $prefix = '#__';
    /**
     * Конструктор класса
     * @param \System\Engine\Config
     */
    public function __construct(Config $config)
    {
        list($host, $user, $password, $database, $port, $prefix) = $config->database;
        $this->prefix = $prefix;
        $this->link = new \mysqli($host, $user, $password, $database, $port);
        $this->link->set_charset('utf8');
    }
    /** 
     * Выполняет запрос к базе данных
     * @param string $sql sql запрос вида select * from таблица where поле = (%s, %i...) 
     * @param array $params Массив!!! с параметрами (параметром) 
     * @return \mysqli_result|true
     */ 
    public function query($sql, array $params = [], $resultMode = MYSQLI_STORE_RESULT) {
        if ($params) {
            $sql = vsprintf($sql, $this->escapeParams($params));
        }
        $this->queryCount++;
        $return = $this->link->query(
            str_replace('#__', $this->prefix, $sql
        ), $resultMode) or die($this->link->error);
        return $return;
    }
    /**
     * Извлекает результирующий ряд в виде ассоциативного массива
     * @link http://php.net/manual/ru/mysqli-result.fetch-assoc.php
     * @return array
     */
    public function fetchAssoc($sql, array $params = []) {
        return $this->query($sql, $params)->fetch_assoc();
    }
    /**
     * Получение строки результирующей таблицы в виде массива
     * @link http://php.net/manual/ru/mysqli-result.fetch-row.php
     * @return array
     */
    public function fetchRow($sql, array $params = [])
    {
        return $this->query($sql, $params)->fetch_row();
    }
    /**
     * Получает число рядов в результирующей выборке
     * @link http://php.net/manual/ru/mysqli-result.fetch-row.php
     * @return int
     */
    public function numRows($sql, array $params = [])
    {
        return $this->query($sql, $params)->num_rows;
    }
    /**
     * Возвращает автоматически генерируемый ID, используя последний запрос
     * @link http://php.net/manual/ru/mysqli-stmt.insert-id.php
     * @return int
     */
    public function lastInsertId()
    {
        return $this->link->insert_id;
    }
    /**
     * Добавляет строку в указанную таблицу
     * @param string $table Имя таблицы
     * @param array $data Массив данных поле => содержимое
     * @return int Возвращает автоматически генерируемый id
     */
    public function insert(string $table, array $data = []) {
        $this->query('INSERT INTO `'. $table .'` 
           (`'. implode('`, `', array_keys($data)) .'`)
           VALUES 
           (`'. implode('`, `', $this->escapeParams(array_values($data))) .'`)
        ');
        return $this->lastInsertId();
    }
    /**
     * Удаляет строку из указанной таблицы по id
     * @param string $table Имя таблицы
     * @param int $id Ид строки
     * @return bool True в случае успешного выполнения запроса
     */
    public function delete(string $table,  int $id) {
        return $this->query('DELETE FROM `'. $table .'` WHERE %s', [$id]);
    }
    /**
     * Обновляет содержимое полей указанной таблицы
     * @param string $table Имя таблицы
     * @param array Массив данных поле => содержимое
     * @return bool True в случае успешного выполнения запроса
     */
    public function update(string $table, int $id, array $data = []) {
        return $this->query('UPDATE `'. $table .'` SET 
           (`'. implode('`, `', array_keys($data)) .'`)
           VALUES 
           (`'. implode('`, `', $this->escapeParams(array_values($data))) .'`)
        ');
    }
    /**
     * Возвращает количество запросов вызванных с помощью MySQLiDB::query()
     * @return int
     */
    public function queryCount()
    {
        return $this->queryCount;
    }
    /**
     * Возвращает обьект \mysqli
     * @return \mysqli
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * Экранирует параметры
     * @param array $params
     * @return array
     */
    protected function escapeParams(array $params) {
        return array_map(function ($param){ 
            return $this->link->real_escape_string($param);
        }, $params); 
    }
}
