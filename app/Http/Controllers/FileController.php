<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\UpdateRequest;
use App\Http\Requests\File\UploadRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\User;
use App\Service\FileService;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * @var FileService
     */
    private FileService $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    /**
     * Показ файлов пользователя
     *
     * @param string $folder
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(string $folder = '')
    {
        $user = User::find(auth()->user()->id);
        $files = $user->files;
        if ($folder !== '') {
            $necessaryFiles = $files->filter(fn($file) => false !== strpos($file->path, $folder));
            if ($necessaryFiles->isNotEmpty()) {
                return FileResource::collection($necessaryFiles);
            }

        }
        return FileResource::collection($files);
    }

    /**
     * Загрузка файла
     *
     * Ожидаемые данные:
     * file (*) - Загружаемый файл
     * life - Время хранения файла (секунды)
     * public_link - Переключатель для генерации публичной ссылки
     * folder - Папка для сохранения файла
     *
     * @param UploadRequest $request
     * @return FileResource Информация о загруженном файле
     */
    public function upload(UploadRequest $request): FileResource
    {
        $data = $request->validated();
        $result = $this->service->upload($data);
        return new FileResource($result);
    }

    /**
     * Скачивание файла
     *
     * @param File $file
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(File $file)
    {
        return Storage::disk('public')->download($file->path . $file->name);
    }

    /**
     * Обновление сведений о файле
     *
     * Ожидаемые данные:
     * name - Имя файла
     * public_link - Переключатель отображения публичной ссылки
     * folder - Название папки
     *
     * @param UpdateRequest $request
     * @param File $file
     * @return FileResource
     */
    public function update(UpdateRequest $request, File $file)
    {
        $data = $request->validated();
        $result = $this->service->update($data, $file);
        return new FileResource($result);
    }

    /**
     * Удаление файла
     *
     * @param File $file Удаляемый файл
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(File $file)
    {
        Storage::disk('public')->delete($file->path . $file->name);
        if (sizeof(Storage::disk('public')->allFiles($file->path))) {
            Storage::disk('public')->deleteDirectory($file->path);
        }
        $file->delete();
        return response()->json();
    }
}
