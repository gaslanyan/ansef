<?php

use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Template::create([
            'id' => 1,
            'name' => 'register',
            'text' => 'An email has been sent to the address <i>address</i> you provided with instructions on how to register.',
            'created_at' => '2019-02-21 19:29:41',
            'updated_at' => '2019-07-30 03:19:59'
        ]);



        App\Models\Template::create([
            'id' => 2,
            'name' => 'success',
            'text' => '<b>Saved successfully.</b>',
            'created_at' => '2019-02-22 01:02:19',
            'updated_at' => '2019-08-09 22:44:32'
        ]);



        App\Models\Template::create([
            'id' => 3,
            'name' => 'wrong',
            'text' => 'Whoops! something went wrong. Please contact webmaster@ansef.org or try again.',
            'created_at' => '2019-02-21 19:32:57',
            'updated_at' => '2019-02-21 19:32:57'
        ]);



        App\Models\Template::create([
            'id' => 4,
            'name' => 'update',
            'text' => '<b>Updated successfully.</b>',
            'created_at' => '2019-02-22 01:01:11',
            'updated_at' => '2019-08-09 22:46:02'
        ]);



        App\Models\Template::create([
            'id' => 5,
            'name' => 'deleted',
            'text' => 'Deleted successfully.',
            'created_at' => '2019-02-28 18:34:25',
            'updated_at' => '2019-02-28 14:34:25'
        ]);



        App\Models\Template::create([
            'id' => 6,
            'name' => 'confirmed',
            'text' => 'Your account is confirmed. You may now login using the password you chose.',
            'created_at' => '2019-02-21 21:07:53',
            'updated_at' => '2019-10-27 21:31:02'
        ]);



        App\Models\Template::create([
            'id' => 7,
            'name' => 'generated_password',
            'text' => 'A new password has been sent to your email address.',
            'created_at' => '2019-03-28 12:02:04',
            'updated_at' => '2019-03-28 08:02:04'
        ]);



        App\Models\Template::create([
            'id' => 9,
            'name' => 'status',
            'text' => 'Login username or password invalid',
            'created_at' => '2019-04-01 22:34:55',
            'updated_at' => '2019-04-01 22:34:55'
        ]);



        App\Models\Template::create([
            'id' => 10,
            'name' => 'unable_user',
            'text' => 'Unable to create new user.',
            'created_at' => '2019-04-08 20:55:41',
            'updated_at' => '2019-04-08 20:55:41'
        ]);



        App\Models\Template::create([
            'id' => 11,
            'name' => 'successfully_registered',
            'text' => 'Your email was used to register to the ANSEF portal. To verify your email and log into your account, please click on the link below:',
            'created_at' => '2019-04-08 20:57:01',
            'updated_at' => '2019-04-08 20:57:01'
        ]);



        App\Models\Template::create([
            'id' => 12,
            'name' => 'registered_by_admin',
            'text' => 'The ANSEF administrator confirmed your registration on the ANSEF portal. Click below to activate your account and change your password.',
            'created_at' => '2019-04-08 20:57:55',
            'updated_at' => '2019-04-08 20:57:55'
        ]);



        App\Models\Template::create([
            'id' => 13,
            'name' => 'thank',
            'text' => 'If you have trouble with the portal, please contact admin@ansef.org.',
            'created_at' => '2019-04-08 20:58:32',
            'updated_at' => '2019-04-08 20:58:32'
        ]);



        App\Models\Template::create([
            'id' => 14,
            'name' => 'new_account',
            'text' => 'A new ANSEF account was created',
            'created_at' => '2019-04-08 20:59:24',
            'updated_at' => '2019-04-08 20:59:24'
        ]);



        App\Models\Template::create([
            'id' => 16,
            'name' => 'waiting',
            'text' => 'Portal administrator has not yet approved your account. Please contact webmaster@ansef.org or try again later.',
            'created_at' => '2019-06-10 23:01:29',
            'updated_at' => '2019-10-11 23:04:56'
        ]);



        App\Models\Template::create([
            'id' => 17,
            'name' => 'successfully_activated',
            'text' => 'Successfully activated.',
            'created_at' => '2019-07-05 06:44:51',
            'updated_at' => '2019-07-05 06:44:51'
        ]);



        App\Models\Template::create([
            'id' => 18,
            'name' => 'activate_account',
            'text' => 'account active',
            'created_at' => '2019-07-05 06:46:56',
            'updated_at' => '2019-07-05 06:46:56'
        ]);



        App\Models\Template::create([
            'id' => 19,
            'name' => 'reject',
            'text' => 'reject the',
            'created_at' => '2019-07-18 08:06:08',
            'updated_at' => '2019-07-18 08:06:08'
        ]);



        App\Models\Template::create([
            'id' => 20,
            'name' => 'account',
            'text' => '&lt;h1&gt;Dear user,&lt;/h1&gt;',
            'created_at' => '2019-07-27 00:06:36',
            'updated_at' => '2019-11-15 21:33:59'
        ]);



        App\Models\Template::create([
            'id' => 21,
            'name' => 'check_email',
            'text' => 'Please check that the email is correct.',
            'created_at' => '2019-07-27 00:10:18',
            'updated_at' => '2019-07-27 00:10:18'
        ]);



        App\Models\Template::create([
            'id' => 22,
            'name' => 'dont_deleted',
            'text' => 'You cannot delete a category that is already assigned to a competition.',
            'created_at' => '2019-08-06 09:47:36',
            'updated_at' => '2019-08-06 10:20:29'
        ]);



        App\Models\Template::create([
            'id' => 23,
            'name' => 'score_count',
            'text' => 'Each competition must have at most 7 score types.',
            'created_at' => '2019-08-29 01:33:43',
            'updated_at' => '2019-08-29 01:33:43'
        ]);



        App\Models\Template::create([
            'id' => 24,
            'name' => 'send_email',
            'text' => 'Your email was sent successfully.',
            'created_at' => '2019-09-27 09:27:05',
            'updated_at' => '2019-09-27 09:27:05'
        ]);



        App\Models\Template::create([
            'id' => 25,
            'name' => 'email_sent',
            'text' => 'We just sent you an email to verify your email address. Check your mailbox and click on the link in the email to confirm your registration.',
            'created_at' => '2019-10-27 21:31:35',
            'updated_at' => '2019-10-27 21:31:35'
        ]);



        App\Models\Template::create([
            'id' => 26,
            'name' => 'send_applicant',
            'text' => 'Your email has been confirmed.  You may now log in.',
            'created_at' => '2019-10-27 22:34:36',
            'updated_at' => '2019-10-27 22:34:36'
        ]);



        App\Models\Template::create([
            'id' => 27,
            'name' => 'email_other',
            'text' => 'An email has been sent to the portal\'s administer who will confirm your account. Once confirmed, you will receive an email with instructions on how to log in.',
            'created_at' => '2019-10-27 22:49:10',
            'updated_at' => '2019-10-27 22:49:10'
        ]);
    }
}
