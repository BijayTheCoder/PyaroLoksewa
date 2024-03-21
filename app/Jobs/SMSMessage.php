<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lines = [];
    protected $from;
    protected $to;

    /**
     * Create a new job instance.
     */
    public function __construct($lines = [])
    {
        $this->lines = $lines;
    
        return $this;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }

       
    
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    public function to($to)
    {
        $this->to = $to;

            return $this;
    }

    public function line($line = '')
    {
        $this->lines[] = $line;

        return $this;
    }

    public function send()
    {
        // dd($this->to);
        if (!$this->from || !$this->to || !count($this->lines)) {
            throw new \Exception('SMS not correct.');
        }
        //   echo config('constants.aakash.auth_token');exit;
        //   print_r($this->lines);exit;
            $lines = implode('',$this->lines);
        //  dd($lines);
        //   return $this->lines;
        try{
            $response = Http::asForm()->post(config('constants.aakash.url'), [
                'auth_token'=> config('constants.aakash.auth_token'),
                'to' => $this->to,
                'text' => $lines
            ]);
            Log::info($response->body());
            Log::info($response->status());
            return $response->status();
        }catch(Exception $e)
        {
            Log::error('SMS error '. $e->getMessage());
            abort(400,'Something went wrong while sending SMS');
        }

        
    }

    public function dryrun($dry = 'yes'): self
    {
        $this->dryrun = $dry;

        return $this;
    }
    
}
