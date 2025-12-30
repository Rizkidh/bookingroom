<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Exception;

class DebugQrCodeGeneration extends Command
{
    protected $signature = 'debug:qrcode {--id=00001}';
    protected $description = 'Debug QR code generation and check system requirements';

    public function handle()
    {
        $this->info('=== QR Code Generation Debug ===');
        
        // Check 1: GD Extension
        $this->info("\n1. Checking GD Extension...");
        if (extension_loaded('gd')) {
            $this->line('   ✅ GD extension is loaded');
        } else {
            $this->error('   ❌ GD extension is NOT loaded');
            $this->warn('   Please enable php_gd2 in php.ini');
        }

        // Check 2: qrcodes directory
        $this->info("\n2. Checking qrcodes directory...");
        $dir = public_path('qrcodes');
        if (!is_dir($dir)) {
            $this->warn("   Directory does not exist: $dir");
            if (mkdir($dir, 0755, true)) {
                $this->line('   ✅ Directory created successfully');
            } else {
                $this->error('   ❌ Failed to create directory');
            }
        } else {
            $this->line("   ✅ Directory exists: $dir");
        }

        if (is_writable($dir)) {
            $this->line('   ✅ Directory is writable');
        } else {
            $this->error('   ❌ Directory is NOT writable');
        }

        // Check 3: Generate test QR code
        $this->info("\n3. Testing QR code generation...");
        $testId = $this->option('id');
        
        try {
            $builder = new Builder(
                writer: new PngWriter(),
                data: (string) $testId,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Low,
                size: 300,
                margin: 10,
            );

            $result = $builder->build();
            $this->line('   ✅ QR code built successfully');

            // Save test file
            $testPath = public_path('qrcodes/test_' . $testId . '.png');
            $result->saveToFile($testPath);
            
            if (file_exists($testPath)) {
                $fileSize = filesize($testPath);
                $this->line("   ✅ QR code saved successfully");
                $this->line("      Path: $testPath");
                $this->line("      Size: $fileSize bytes");
                
                // Clean up test file
                unlink($testPath);
                $this->line("   ✅ Test file cleaned up");
            } else {
                $this->error('   ❌ QR code file was not created');
            }

        } catch (Exception $e) {
            $this->error('   ❌ QR code generation failed');
            $this->error('      Error: ' . $e->getMessage());
            $this->error('      Trace: ' . $e->getTraceAsString());
        }

        // Check 4: Check logs
        $this->info("\n4. Checking error logs...");
        $logFile = storage_path('logs/qrcode_errors.log');
        if (file_exists($logFile)) {
            $this->warn("   QR code errors found in: $logFile");
            $this->line("\n   Last 10 errors:");
            $lines = array_slice(file($logFile), -10);
            foreach ($lines as $line) {
                $this->line('   ' . trim($line));
            }
        } else {
            $this->line('   ✅ No error log file found (no errors yet)');
        }

        $this->info("\n=== Debug Complete ===\n");
    }
}
