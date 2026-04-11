<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\EmailTemplate::updateOrCreate(['id' => 1], [
            'reset_title' => 'Password Reset Request',
            'reset_color' => '#0a192f',
            'reset_body'  => '<p>We received a request to reset your password. Click the button below to set a new password.</p>',

            'pub_title'   => 'New Training Notification',
            'pub_color'   => '#1e3a8a',
            'pub_body'    => '<p>A new training program <strong>{training_title}</strong> has been scheduled. Kindly nominate participants.</p>',

            'nom_title'   => 'Training Nomination Received',
            'nom_color'   => '#0284c7',
            'nom_body'    => '<p>Dear {nominee_name}, you have been nominated for the <strong>{training_title}</strong> program.</p>',

            'app_title'   => 'Nomination Approved',
            'app_color'   => '#16a34a',
            'app_body'    => '<p>Congratulations! Your nomination for <strong>{training_title}</strong> has been approved.</p>',

            'rem_title'   => 'Training Completed!',
            'rem_color'   => '#0284c7',
            'rem_body'    => '<p>Dear {nominee_name}, you have successfully completed <strong>{training_title}</strong>. Please provide your feedback.</p>',
        ]);
    
    }
    
}