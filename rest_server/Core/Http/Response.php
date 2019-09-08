<?php

namespace Core\Http;

/**
 * Класс формирующий HTTP-ответ
 */
class Response 
{
    use \Singleton;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $statusTexts = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
    ];

    /**
     * Хранит тело ответа
     *
     * @var string
     */
    protected $content;

    /**
     * Получение текста по коду статуса
     *
     * @param  int $code
     * @return string
     */
    public function getStatusTextByCode(int $code): string
    {
        return (string) isset($this->statusTexts[$code]) 
            ? $this->statusTexts[$code]
            : 'unknown status';
    }

    /**
     * Добавление заголовка к HTTP-ответу
     *
     * @param string $header
     */
    public function setHeader(string $header) 
    {
        $this->headers[] = $header;
    }

    /**
     * Подготовка тела сообщения к отправке в json-формате
     *
     * @param string $content
     */
    public function setContent($content) 
    {
        $this->content = json_encode($content);
    }

    /**
     * Подготовка заголовка со статусом ответа
     *
     * @param string $code Код ответа
     */
    public function setStatus($code)
    {
        $this->setHeader('HTTP/1.1 ' . $code . ' ' . $this->getStatusTextByCode($code));
    }

    /**
     * Отправка HTTP-ответа
     */
    public function render()
    {
        // Если заголовки еще не отправлялись, то самое время это сделать
        if (!headers_sent()) {
            foreach ($this->headers as $header) {
                header($header, true);
            }
        }

        // Отправка тела сообщения
        if ($this->content) {
            $output = $this->content;
            echo $output;
        }
    }
}
