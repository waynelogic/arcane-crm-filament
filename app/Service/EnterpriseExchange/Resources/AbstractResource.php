<?php namespace App\Service\EnterpriseExchange\Resources;

use Illuminate\Support\Facades\Storage;

abstract class AbstractResource
{
    const EXCHANGE_FOLDER = 'exchange';
    public mixed $fileName;
    public  string $filePath;

    /**
     * Проверка аккаунта
     * @return string
     */
    public function checkauth(): string
    {
        $answer = [
            "success",
            config('session.cookie'),
            session()->getId(),
            'timestamp='.time()
        ];
        return $this->answer($answer);
    }

    /**
     * Инициализация ресурса
     * @return string
     */
    public function init() : string
    {
        $answer = [
            'zip=' . $this->zip(),
            'file_limit=' . '52428800',
        ];
        $answer[] = 'sessid=' . session()->getId();
        $answer[] = 'version=3.1';
        return $this->answer($answer);
    }

    public function file() : string
    {
        if (!Storage::exists(self::EXCHANGE_FOLDER)) Storage::makeDirectory(self::EXCHANGE_FOLDER);
        $path = $_GET['filename'];
        $file = file_get_contents('php://input');
        Storage::put(self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . $path, $file);
        return $this->success();
    }

    public function import() : string
    {
        $this->fileName = $_GET['filename'];
        $this->filePath = self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . $this->fileName;
        if (!Storage::exists($this->filePath)) {
            return $this->failure('File not found');
        }
        $this->parse();
        return $this->success();
    }

    abstract public function parse();
    public function failure($message) : string
    {
        return $this->answer(['failure' . $message]);
    }
    public function success() : string
    {
        return $this->answer(['success']);
    }

    public function answer(array $array) : string
    {
        return implode("\n", $array);
    }

    public function getStoragePath($file_path) : string
    {
        return self::EXCHANGE_FOLDER . DIRECTORY_SEPARATOR . $file_path;
    }

    private function zip() : string
    {
        return 'no';
    }
}
