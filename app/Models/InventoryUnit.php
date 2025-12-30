<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Log;
use Exception;

class InventoryUnit extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inventory_item_id',
        'serial_number',
        'photo',
        'condition_status',
        'current_holder',
        'qr_code',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            try {
                // Generate ID
                if (!$model->id) {
                    $lastId = self::orderBy('id', 'desc')->value('id');
                    $model->id = $lastId
                        ? str_pad(((int) $lastId) + 1, 5, '0', STR_PAD_LEFT)
                        : '00001';
                }

                // Validate inventory_item_id exists
                if (!$model->inventory_item_id) {
                    throw new Exception('inventory_item_id is required');
                }

                // Ensure folder exists
                $dir = public_path('qrcodes');
                if (!is_dir($dir)) {
                    if (!mkdir($dir, 0755, true)) {
                        throw new Exception('Failed to create qrcodes directory at: ' . $dir);
                    }
                }

                // Validate folder is writable
                if (!is_writable($dir)) {
                    throw new Exception('qrcodes directory is not writable: ' . $dir);
                }

                // Validate GD extension is available
                if (!extension_loaded('gd')) {
                    throw new Exception('GD extension is required for QR code generation. Please enable php_gd2 in php.ini');
                }

                // Generate QR Code using Endroid v6 API
                $builder = new Builder(
                    writer: new PngWriter(),
                    data: (string) $model->id,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::Low,
                    size: 300,
                    margin: 10,
                );

                $result = $builder->build();

                $path = 'qrcodes/' . $model->id . '.png';
                $fullPath = public_path($path);
                
                // Save QR code file
                $result->saveToFile($fullPath);

                // Verify file was created
                if (!file_exists($fullPath)) {
                    throw new Exception('QR code file was not created at: ' . $fullPath);
                }

                // Verify file has content
                if (filesize($fullPath) === 0) {
                    throw new Exception('QR code file is empty at: ' . $fullPath);
                }

                $model->qr_code = $path;
                
                Log::info('QR code generated successfully', [
                    'inventory_unit_id' => $model->id,
                    'path' => $path,
                    'file_size' => filesize($fullPath),
                ]);

            } catch (Exception $e) {
                Log::error('Failed to generate QR code', [
                    'error_message' => $e->getMessage(),
                    'inventory_item_id' => $model->inventory_item_id ?? 'unknown',
                    'stack_trace' => $e->getTraceAsString(),
                ]);
                
                // Log error to file for easier debugging
                $logFile = storage_path('logs/qrcode_errors.log');
                file_put_contents(
                    $logFile,
                    date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . PHP_EOL,
                    FILE_APPEND
                );
                
                // Set qr_code to null if generation fails
                // Model will still be created but without QR code
                $model->qr_code = null;
            }
        });
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id', 'id');
    }
}
