<?php

namespace App\Jobs;

use App\Models\HafalanAudio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;

class CompressAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hafalanId;
    protected $originalPath;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct($hafalanId, $originalPath)
    {
        $this->hafalanId = $hafalanId;
        $this->originalPath = $originalPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $audio = HafalanAudio::find($this->hafalanId);
            
            if (!$audio) {
                Log::error("HafalanAudio record not found: {$this->hafalanId}");
                return;
            }

            // Update status to processing
            $audio->update(['status' => 'processing']);

            // Get full path
            $fullPath = Storage::disk('public')->path($this->originalPath);
            
            if (!file_exists($fullPath)) {
                Log::error("Audio file not found: {$fullPath}");
                $audio->update(['status' => 'failed']);
                return;
            }

            // Get file info
            $originalSize = filesize($fullPath);
            $pathInfo = pathinfo($fullPath);
            
            // Store original size first
            $audio->update([
                'original_audio_size' => $originalSize,
                'compressed_audio_size' => $originalSize, // Default: same as original
            ]);

            // Try to compress, but if FFmpeg not available, just mark as ready with original
            $ffmpegAvailable = $this->isFFmpegAvailable();
            
            if ($ffmpegAvailable) {
                try {
                    // Create compressed filename
                    $compressedFilename = $pathInfo['filename'] . '_compressed.' . $pathInfo['extension'];
                    $compressedPath = $pathInfo['dirname'] . '/' . $compressedFilename;
                    
                    // Initialize FFmpeg with explicit binary paths
                    $ffmpegPath = env('FFMPEG_BINARY', $this->getFFmpegPath());
                    $ffprobePath = env('FFPROBE_BINARY', $this->getFFprobePath());
                    
                    $ffmpeg = FFMpeg::create([
                        'ffmpeg.binaries'  => $ffmpegPath,
                        'ffprobe.binaries' => $ffprobePath,
                        'timeout'          => 3600,
                        'ffmpeg.threads'   => 12,
                    ]);

                    // Open audio file
                    $ffmpegAudio = $ffmpeg->open($fullPath);
                    
                    // Set format with compression
                    $format = new Mp3();
                    $format->setAudioKiloBitrate(64); // Compress to 64kbps
                    $format->setAudioChannels(1); // Mono
                    
                    // Save compressed file
                    $ffmpegAudio->save($format, $compressedPath);
                    
                    // Get compressed file size
                    $compressedSize = filesize($compressedPath);
                    $compressionRatio = (($originalSize - $compressedSize) / $originalSize) * 100;
                    
                    // Update audio record with compression data
                    $relativePath = str_replace(Storage::disk('public')->path(''), '', $compressedPath);
                    
                    $audio->update([
                        'file_path' => $relativePath,
                        'original_audio_size' => $originalSize,
                        'compressed_audio_size' => $compressedSize,
                        'compression_ratio' => round($compressionRatio, 2),
                        'is_compressed' => true,
                        'status' => 'ready',
                    ]);
                    
                    // Delete original file if compression successful and smaller
                    if (file_exists($fullPath) && $compressedSize < $originalSize) {
                        unlink($fullPath);
                    }
                    
                    Log::info("Audio compressed successfully", [
                        'hafalan_audio_id' => $this->hafalanId,
                        'original_size' => $this->formatBytes($originalSize),
                        'compressed_size' => $this->formatBytes($compressedSize),
                        'saved' => $this->formatBytes($originalSize - $compressedSize),
                        'ratio' => round($compressionRatio, 2) . '%',
                    ]);
                    
                } catch (\Exception $ffmpegError) {
                    // FFmpeg error - mark as ready with original file
                    Log::warning("FFmpeg compression failed, using original file", [
                        'hafalan_audio_id' => $this->hafalanId,
                        'reason' => $ffmpegError->getMessage(),
                    ]);
                    
                    $audio->update([
                        'is_compressed' => false,
                        'status' => 'ready',
                    ]);
                }
            } else {
                // FFmpeg not available - use original file
                Log::info("FFmpeg not available, using original file", [
                    'hafalan_audio_id' => $this->hafalanId,
                ]);
                
                $audio->update([
                    'original_audio_size' => $originalSize,
                    'compressed_audio_size' => $originalSize,
                    'is_compressed' => false,
                    'status' => 'ready',
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error("Audio processing failed", [
                'hafalan_audio_id' => $this->hafalanId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Update status to failed
            if (isset($audio)) {
                $audio->update(['status' => 'failed']);
            }
            
            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Audio compression job failed permanently", [
            'hafalan_audio_id' => $this->hafalanId,
            'error' => $exception->getMessage(),
        ]);
        
        // Update audio status to failed
        if ($audio = HafalanAudio::find($this->hafalanId)) {
            $audio->update(['status' => 'failed']);
        }
    }

    /**
     * Check if FFmpeg is available on system
     */
    /**
     * Get FFmpeg binary path
     */
    private function getFFmpegPath(): string
    {
        // Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $paths = [
                'C:\\ffmpeg\\bin\\ffmpeg.exe',
                'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
                'C:\\Program Files (x86)\\ffmpeg\\bin\\ffmpeg.exe',
            ];
            
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
            
            // Try system PATH
            $output = shell_exec('where ffmpeg 2>nul');
            if (!empty($output)) {
                return trim($output);
            }
        }
        
        // Linux/Mac
        if (file_exists('/usr/local/bin/ffmpeg')) {
            return '/usr/local/bin/ffmpeg';
        }
        if (file_exists('/usr/bin/ffmpeg')) {
            return '/usr/bin/ffmpeg';
        }
        
        return 'ffmpeg';
    }

    /**
     * Get FFprobe binary path
     */
    private function getFFprobePath(): string
    {
        // Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $paths = [
                'C:\\ffmpeg\\bin\\ffprobe.exe',
                'C:\\Program Files\\ffmpeg\\bin\\ffprobe.exe',
                'C:\\Program Files (x86)\\ffmpeg\\bin\\ffprobe.exe',
            ];
            
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
            
            // Try system PATH
            $output = shell_exec('where ffprobe 2>nul');
            if (!empty($output)) {
                return trim($output);
            }
        }
        
        // Linux/Mac
        if (file_exists('/usr/local/bin/ffprobe')) {
            return '/usr/local/bin/ffprobe';
        }
        if (file_exists('/usr/bin/ffprobe')) {
            return '/usr/bin/ffprobe';
        }
        
        return 'ffprobe';
    }

    /**
     * Check if FFmpeg is available on system
     */
    private function isFFmpegAvailable(): bool
    {
        try {
            // Try to create FFmpeg instance - this will fail if ffmpeg is not available
            $ffmpegPath = $this->getFFmpegPath();
            $ffprobePath = $this->getFFprobePath();
            
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => $ffmpegPath,
                'ffprobe.binaries' => $ffprobePath,
                'timeout'          => 10,
                'ffmpeg.threads'   => 1,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::debug("FFmpeg availability check failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}