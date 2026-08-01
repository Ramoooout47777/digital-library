<?php
// app/Services/UploadService.php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Modifiers\CoverModifier;
use Intervention\Image\Modifiers\ResizeModifier;

class UploadService
{
    protected $disk = 'public';
    protected $allowedImageTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    protected $allowedPdfTypes = ['pdf'];
    protected $maxImageSize = 2048; // KB
    protected $maxPdfSize = 51200; // 50MB

    /**
     * Upload an image file
     */
    public function uploadImage($file, $path, $options = [])
    {
        // Default options
        $options = array_merge([
            'width' => null,
            'height' => null,
            'quality' => 90,
            'optimize' => true,
            'resize' => true,
            'maintain_aspect' => true,
        ], $options);

        // Validate file
        $this->validateFile($file, 'image');

        // Generate filename
        $filename = $this->generateFilename($file);
        $fullPath = $this->cleanPath($path . '/' . $filename);

        // Process image
        if ($options['optimize'] || $options['resize']) {
            $image = $this->processImage($file, $options);
            Storage::disk($this->disk)->put($fullPath, $image);
        } else {
            Storage::disk($this->disk)->putFileAs($path, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload a PDF file
     */
    public function uploadPdf($file, $path, $options = [])
    {
        // Default options
        $options = array_merge([
            'generate_preview' => true,
            'watermark' => false,
            'encrypt' => false,
            'password' => null,
        ], $options);

        // Validate file
        $this->validateFile($file, 'pdf');

        // Generate filename
        $filename = $this->generateFilename($file);
        $fullPath = $this->cleanPath($path . '/' . $filename);

        // Upload PDF
        Storage::disk($this->disk)->putFileAs($path, $file, $filename);

        // Generate preview (optional)
        if ($options['generate_preview']) {
            $this->generatePdfPreview($fullPath);
        }

        return $fullPath;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple($files, $path, $options = [])
    {
        $uploaded = [];
        
        foreach ($files as $file) {
            if ($this->isImage($file)) {
                $uploaded[] = $this->uploadImage($file, $path, $options);
            } elseif ($this->isPdf($file)) {
                $uploaded[] = $this->uploadPdf($file, $path, $options);
            }
        }
        
        return $uploaded;
    }

    /**
     * Delete a file
     */
    public function delete($path)
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = $this->cleanPath($path);
        
        if (Storage::disk($this->disk)->exists($fullPath)) {
            Storage::disk($this->disk)->delete($fullPath);
            
            // Delete thumbnails if they exist
            $this->deleteThumbnails($fullPath);
            
            return true;
        }
        
        return false;
    }

    /**
     * Replace an existing file with a new one
     */
    public function replace($oldPath, $newFile, $path, $options = [])
    {
        // Delete old file
        $this->delete($oldPath);
        
        // Upload new file
        if ($this->isImage($newFile)) {
            return $this->uploadImage($newFile, $path, $options);
        } elseif ($this->isPdf($newFile)) {
            return $this->uploadPdf($newFile, $path, $options);
        }
        
        return null;
    }

    /**
     * Get file URL
     */
    public function getUrl($path)
    {
        if (empty($path)) {
            return null;
        }
        
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Get file size
     */
    public function getSize($path)
    {
        if (empty($path)) {
            return 0;
        }
        
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->size($path);
        }
        
        return 0;
    }

    /**
     * Generate a unique filename
     */
    protected function generateFilename($file)
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::uuid()->toString();
        
        return $name . '.' . $extension;
    }

    /**
     * Process image (resize, optimize)
     */
    protected function processImage($file, $options)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->decodePath($file->getRealPath());

        // Resize if needed
        if ($options['resize'] && ($options['width'] || $options['height'])) {
            $width = $options['width'] ?? null;
            $height = $options['height'] ?? null;

            if ($options['maintain_aspect'] && $width && $height) {
                $image = $image->modify(new CoverModifier($width, $height));
            } elseif ($width || $height) {
                $image = $image->modify(new ResizeModifier($width, $height));
            }
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'upload');
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $targetPath = $tempPath . '.' . $extension;
        rename($tempPath, $targetPath);

        $image->save($targetPath, quality: $options['quality']);
        $contents = file_get_contents($targetPath);
        unlink($targetPath);

        return $contents;
    }

    /**
     * Generate PDF preview (thumbnail)
     */
    protected function generatePdfPreview($path)
    {
        // This requires additional packages like spatie/pdf-to-image
        // Implementation will be added later
    }

    /**
     * Delete thumbnails
     */
    protected function deleteThumbnails($path)
    {
        $directory = dirname($path);
        $filename = basename($path);
        $thumbnails = Storage::disk($this->disk)->files($directory . '/thumbnails');
        
        foreach ($thumbnails as $thumbnail) {
            if (str_contains($thumbnail, $filename)) {
                Storage::disk($this->disk)->delete($thumbnail);
            }
        }
    }

    /**
     * Clean path (remove duplicate slashes)
     */
    protected function cleanPath($path)
    {
        return preg_replace('#/+#', '/', $path);
    }

    /**
     * Validate file type and size
     */
    protected function validateFile($file, $type)
    {
        if ($type === 'image') {
            if (!$this->isImage($file)) {
                throw new \Exception('File must be an image (jpg, jpeg, png, webp, gif)');
            }
            
            if ($file->getSize() > $this->maxImageSize * 1024) {
                throw new \Exception('Image size must not exceed ' . $this->maxImageSize . 'KB');
            }
        } elseif ($type === 'pdf') {
            if (!$this->isPdf($file)) {
                throw new \Exception('File must be a PDF');
            }
            
            if ($file->getSize() > $this->maxPdfSize * 1024) {
                throw new \Exception('PDF size must not exceed ' . $this->maxPdfSize . 'KB');
            }
        }
        
        return true;
    }

    /**
     * Check if file is an image
     */
    protected function isImage($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, $this->allowedImageTypes);
    }

    /**
     * Check if file is a PDF
     */
    protected function isPdf($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, $this->allowedPdfTypes);
    }
}