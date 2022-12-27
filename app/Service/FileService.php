<?php

namespace App\Service;

use App\Jobs\DeleteFile;
use App\Models\File;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
            Storage::disk('public')->putFileAs($folder, $file, $fileName, 'private');
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

            if (isset($data['life'])) {
                DeleteFile::dispatch($newFile)->delay($data['life']);
            }

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
            $oldPathArray = explode('/', $oldPath);
            $newPath = '/' . $oldPathArray[1] . '/';

            if (isset($data['folder'])) {
                $newPath .= $data['folder'] . '/';
                $data['path'] = $newPath;
                unset($data['folder']);
            } elseif (sizeof($oldPathArray) > 3) {
                $newPath .= $oldPathArray[2] . '/';
            }

            $olFileName = array_pop($oldPathArray);
            $newPath .= isset($data['name'])
                ? preg_replace('/\w+(?=\.\w+$)/', $data['name'], $olFileName)
                : $olFileName;

            if (Storage::disk('public')->exists($newPath)) {
                throw new FileException();
            }

            Storage::disk('public')->move($oldPath, $newPath);
            if (Storage::disk('public')->allFiles($file->path) <= 0) {
                Storage::disk('public')->deleteDirectory($file->path);
            }

            if (isset($data['public_link'])) {
                $data['public_link'] = $data['public_link'] ? route('public.images') . '?image=' . md5($newPath) : null;
            }

            $pathInfo = pathinfo($newPath);
            $data['name'] = $data['name'] . '.' . $pathInfo['extension'];
            $file->update($data);

            Db::commit();
        } catch (FileException $fileException) {
            DB::rollBack();
            throw new HttpResponseException(response()->json(['message' => 'В этой папке уже существую такой файл'], 500));
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Ошибка загрузки файла - ' . $exception->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'Ошибка во время выполнения операции'], 500));
        }

        return $file;
    }

}