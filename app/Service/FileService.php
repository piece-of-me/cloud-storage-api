<?php

namespace App\Service;

use App\Models\File;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileService
{

    /**
     * Загрузка файла
     *
     * @param array $data Информация о файле
     * @return File Загруженный файл
     */
    public function upload(array $data): File
    {
        try {
            DB::beginTransaction();

            $file = $data['file'];
            $userId = auth()->user()->id;
            $folder = '/user_' . $userId . (isset($data['folder']) ? '/' . $data['folder'] : '') . '/';
            $fileName = $file->getClientOriginalName();
            $filePath = Storage::disk('public')->put('', $file, 'private');
            Storage::disk('public')->move($filePath, $folder . $fileName);
            $fileInfo = [
                'user_id' => $userId,
                'name' => $fileName,
                'path' => $folder,
                'size' => Storage::disk('public')->size($folder . $fileName),
            ];
            if (isset($data['public_link'])) {
                $fileInfo['public_link'] = $data['public_link'] ? route('public.images') . '?image=' . md5($folder . $fileName) : null;
            }

            $newFile = File::create($fileInfo);

            Db::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Ошибка загрузки файла - ' . $exception->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'Ошибка во время выполнения операции'], 500));
        }
        return $newFile;
    }

    /**
     * Обновление информации о файле
     *
     * @param array $data Информация о файле
     * @param File $file Обновляемый файл
     * @return File Обновленный файл
     */
    public function update(array $data, File $file): File
    {
        try {
            DB::beginTransaction();

            $oldPath = $file->path . $file->name;
            $newPath = preg_replace('/(?<=\/)(\w+)(?=\.)/', $data['name'], $oldPath);
            Storage::disk('public')->move($oldPath, $newPath);

            if (isset($data['public_link']) && $data['public_link']) {
                $data['public_link'] = 'public link';
            }

            $pathInfo = pathinfo($newPath);
            $data['name'] = $data['name'] . '.' . $pathInfo['extension'];
            $file->update($data);

            Db::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Ошибка загрузки файла - ' . $exception->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'Ошибка во время выполнения операции'], 500));
        }

        return $file;
    }

}