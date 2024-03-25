<?php

namespace app\models;

use Yii;
use yii\web\Response;
use ZipArchive;

class ImageUpload extends Image {

    public $image;

    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5],
        ];
    }


    public function upload(): bool
    {
        if ($this->validate()) {
            foreach ($this->image as $file) {
                if ($this->fileExists($this->currentImage($file))) {
                    $filename = $this->generateFilename($file);

                    $file->saveAs($this->getFolder() . $filename);
                    $imageName = $filename;
                } else {
                    $file->saveAs($this->getFolder() . $this->currentImage($file));
                    $imageName = $this->currentImage($file);
                }
                var_dump($imageName);
                $loadingTime = date('H:i:s');
                $create_at = date('Y.m.d');

                $this->saveImage($imageName, $loadingTime, $create_at);
            }

            return true;
        } else {
            return false;
        }
    }

    public function download($filename): Response
    {
        $extensionPosition = strrpos($filename, '.');
        $filenameWithoutExtension = substr($filename, 0, $extensionPosition);

        $path = Yii::getAlias('@web') . 'uploads/';
        $pathImageZipName = $path . 'zip/' . $filenameWithoutExtension . '.zip';

        $zip = new ZipArchive();
        $zip->open($pathImageZipName, ZipArchive::CREATE);
        $zip->addFile($path . $filename);
        $zip->close();

        return Yii::$app->response->sendFile($pathImageZipName);
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename($file): string
    {
        return strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
    }

    public function fileExists($currentImage)
    {
        if(!empty($currentImage) && $currentImage != null)
        {
            return file_exists($this->getFolder() . $currentImage);
        }
    }

    public function currentImage($file)
    {
        return $this->translit($file->baseName) . '.' . $file->extension;
    }

    public function translit($st)
    {
        $st = mb_strtolower($st, "utf-8");
        $st = str_replace([
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к',
            'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х',
            'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'
        ], [
            '_', '_', '.', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_',
            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k',
            'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h',
            'j', 'i', 'e', '_', 'zh', 'ts', 'ch', 'sh', 'shch',
            '', 'yu', 'ya'
        ], $st);
        $st = preg_replace("/[^a-z0-9_.]/", "", $st);
        $st = trim($st, '_');

        $prev_st = '';
        do {
            $prev_st = $st;
            $st = preg_replace("/_[a-z0-9]_/", "_", $st);
        } while ($st != $prev_st);

        $st = preg_replace("/_{2,}/", "_", $st);
        return $st;
    }
}