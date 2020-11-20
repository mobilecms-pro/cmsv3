<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */
namespace System\Engine\Http;


use Psr\Http\Message\UploadedFileInterface;
/**
 * Класс представляющий файл загруженный через HTTP запрос
 * @author KpuTuK
 */
class UploadedFile implements UploadedFileInterface {
    protected $tempName;
    protected $clientFileName;
    protected $clientMediaType;
    protected $error;
    protected $size;
    public function __construct($data) {
        $this->tempName = $data['tmp_name'];
        $this->clientFileName = $data['name'];
        $this->clientMediaType = $data['type'];
        $this->error = $data['error'];
        $this->size = $data['size'];
    }
    /**
     * Возвращает имя файла на клиенте
     * @return string
     */
    public function getClientFilename() {
        return $this->clientFileName;
    }
    /**
     * Возвращает MIME тип файла
     * @return string
     */
    public function getClientMediaType() {
        return $this->clientMediaType;
    }
    /**
     * Возвращает код ошибки
     * @return int
     */
    public function getError() {
        return $this->error;
    }
    /**
     * Возвращает размер файла
     * @return int
     */
    public function getSize() {
        return $this->size;
    }
    /**
     * Возвращает обьект потока файла
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getStream() {
        return new Stream($this->tempName);
    }
    /**
     * Перемещает файл по указанному пути
     * @param string $targetPath
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function moveTo($targetPath) {
        if ( ! is_dir($targetPath)) {
            throw new \InvalidArgumentException('Путь не существует!');
        }
        if (
            ( ! is_uploaded_file($this->tempName)) &&
            ( ! move_uploaded_file(
                $this->tempName, 
                $targetPath.$this->clientFileName
            ))
        ) {
            throw new \RuntimeException('Ошибка перемещения файла!');
        }
    }
}