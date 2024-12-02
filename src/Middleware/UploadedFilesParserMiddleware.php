<?php

namespace App\Middleware;

use Laminas\Diactoros\{Stream, UploadedFile};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface, UploadedFileInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class UploadedFilesParserMiddleware
 * @package App\Middleware
 */
class UploadedFilesParserMiddleware implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!count($_FILES)) {
            return $handler->handle($request);
        }

        $uploaded_files = [];
        foreach ($_FILES as $key => $file) {
            $uploaded_files[$key] = $this->getUploadedFileLeaves($file);
        }

        return $handler->handle($request->withUploadedFiles($uploaded_files));
    }

    /**
     * @param array{'tmp_name': string|string[], 'size': int|int[], 'error': int|int[], 'name': string|string[], 'type': string|string[]} $uploaded_files
     * @return UploadedFileInterface|UploadedFileInterface[]
     */
    protected function getUploadedFileLeaves(array $uploaded_files): UploadedFileInterface|array
    {
        $new_file = [];

        if (isset($uploaded_files['tmp_name']) && !is_array($uploaded_files['tmp_name'])) {
            $new_file = new UploadedFile(
                new Stream(fopen($uploaded_files['tmp_name'], 'r')),
                $uploaded_files['size'],
                $uploaded_files['error'],
                $uploaded_files['name'],
                $uploaded_files['type']
            );
        } elseif (isset($uploaded_files['tmp_name'][0])) {
            foreach ($uploaded_files['tmp_name'] as $key => $value) {
                $new_file[$key] = new UploadedFile(
                    new Stream(fopen($uploaded_files['tmp_name'][$key], 'r')),
                    $uploaded_files['size'][$key],
                    $uploaded_files['error'][$key],
                    $uploaded_files['name'][$key],
                    $uploaded_files['type'][$key]
                );
            }
        } else {
            foreach (array_keys($uploaded_files['tmp_name']) as $index) {
                $new_array = array_combine(
                    array_keys($uploaded_files),
                    array_column($uploaded_files, $index)
                );

                $new_file[$index] = $this->getUploadedFileLeaves($new_array);
            }
        }

        return $new_file;
    }
}
