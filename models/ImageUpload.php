<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ImageUpload extends Image {

    public $image;

    public function rules()
    {
        return [
//            [['image'], 'required'],
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5],
        ];
    }


    public function upload(): bool
    {
        if ($this->validate()) {
            foreach ($this->image as $file) {
                $startSaveImage = microtime(true);
//                $imageName = '';
                if ($this->fileExists($this->translit($file->baseName) . '.' . $file->extension)) {
                    $filename = $this->generateFilename($file);

                    $file->saveAs($this->getFolder() . $filename);
                    $imageName = $filename;
                } else {
                    $file->saveAs($this->getFolder() . $this->translit($file->baseName) . '.' . $file->extension);
                    $imageName = $this->translit($this->translit($file->baseName) . '.' . $file->extension);
                }

                $loadingTime = microtime(true) - $startSaveImage;
                $create_at = date('Y.m.d');

                $this->saveImage($imageName, $loadingTime, $create_at);
            }

            return true;
        } else {
            return false;
        }
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