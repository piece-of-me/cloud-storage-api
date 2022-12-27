<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;

class SpaceController extends Controller
{
    /**
     * Максимально допустимый размер хранилища для одного пользователя
     */
    private const MAX_SIZE = 104857600;

    /**
     * Отображение размера файлов на диске
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function total()
    {
        $user = auth()->user();
        $files = $user->files;
        $root = '/user_' . $user->id . '/';
        $current = $files->sum('size');

        return response()->json(['data' => [
            'list' => $files->map(fn($file) => [
                'id' => $file->id,
                'name' => $file->name,
                'folder' => str_replace($root, '', $file->path),
                'size' => $file->size,
            ]),
            'current' => $current,
            'free' => self::MAX_SIZE - $current,
            'total' => self::MAX_SIZE,
        ]]);
    }

    /**
     * Получение размера файлов в папке
     *
     * @param string $folder Название папки
     * @return \Illuminate\Http\JsonResponse
     */
    public function folder(string $folder)
    {
        $files = auth()->user()->files->filter(fn($file) => false !== strpos($file->path, $folder));
        if ($files->isEmpty()) {
            throw new HttpResponseException(response()->json(['message' => 'folder "' . $folder . '" not found'], 404));
        }
        return response()->json(['data' => [
            'folder' => $folder,
            'list' => $files->map(fn($file) => [
                'id' => $file->id,
                'name' => $file->name,
                'size' => $file->size,
            ]),
            'total' => $files->sum('size')
        ]]);
    }
}
