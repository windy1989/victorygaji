<?php

namespace App\Jobs;

use App\Models\Payroll;
use App\Models\User;
use App\Models\HistoryEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class QueueMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email,$name,$subject,$data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$name,$data)
    {
        $this->email = $email;
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            Mail::to($this->email)->send(new SendMail($this->data));
            HistoryEmail::create([
                'payroll_id'    => $this->data['result']['id'],
                'date_process'  => date('Y-m-d H:i:s'),
                'status'        => '2',
            ]);
        }catch(\Exception $e){
            info($e->getMessage());
            HistoryEmail::create([
                'payroll_id'    => $this->data['result']['id'],
                'date_process'  => date('Y-m-d H:i:s'),
                'status'        => '1',
            ]);
        }
    }
}
