<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PublicDownloadController extends Controller
{
    /**
     * Скачивание файла по публичной ссылке
     *
     * Ожидаемые данные:
     * $_GET('image') - хэшированное путь и имя файла
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->only(['image']), [
            'image' => 'required|string'
        ], [
            'image.required' => 'Необходимо указать параметр "image"',
            'image.string' => 'Параметр "image" должен быть строкой'
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json($validator->errors(), 422));
        }

        $data = $validator->validated();
        $currentPublicLink = route('public.images') . '?image=' . $data['image'];
        $collection = DB::table('files')->where('public_link', '=', $currentPublicLink)->get();

        if ($collection->isEmpty()) {
            throw new HttpResponseException(response()->json([], 404));
        }
        $file = $collection->first();
        return Storage::disk('public')->download($file->path . $file->name);
    }
}
