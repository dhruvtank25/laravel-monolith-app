<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'incomplete', 'kyc pending', 'ubo pending', 'approval'])->after('id')->default('active');
            $table->integer('role_id')->after('status');
            $table->tinyInteger('can_login')->after('role_id')->default(1);
            $table->string('mango_user_id', 255)->after('can_login')->nullable();
            $table->string('mango_wallet_id', 255)->after('mango_user_id')->nullable();
            $table->renameColumn('name', 'first_name');
            $table->string('last_name')->after('name');
            $table->text('phone_number')->after('last_name');
            $table->date('birth_date')->after('email_verified_at')->default('2001-01-01');
            $table->string('nationality')->after('birth_date')->nullable();

            $table->string('user_name')->after('nationality')->nullable();
            $table->tinyInteger('is_anonymous')->after('user_name')->default(0);
            $table->string('latitude')->after('is_anonymous')->nullable();
            $table->string('longitude')->after('latitude')->nullable();
            $table->string('street')->after('longitude')->nullable();
            $table->string('house_no')->after('street')->nullable();  // Remove in Future
            $table->string('post_code')->after('house_no')->nullable();
            $table->string('place')->after('post_code')->nullable();
            $table->string('country')->after('place')->default('Germany');
            $table->string('country_code')->after('country')->default('DE');
            $table->tinyInteger('different_work')->after('country_code')->default(0);
            $table->string('work_latitude')->after('different_work')->nullable();
            $table->string('work_longitude')->after('work_latitude')->nullable();
            $table->string('work_street')->after('work_longitude')->nullable();
            $table->string('work_house_no')->after('work_street')->nullable();  // Remove in Future
            $table->string('work_post_code')->after('work_house_no')->nullable();
            $table->string('work_place')->after('work_post_code')->nullable();
            $table->string('work_country')->after('work_place')->default('Germany');
            $table->string('work_country_code')->after('work_country')->default('DE');
            $table->tinyInteger('show_on_map')->after('work_country_code')->default(0);
            /** Address End */

            /** Futher Coach Details */
            $table->string('community', 255)->after('show_on_map')->nullable()->comment('community or club');
            $table->string('language', 255)->after('community')->nullable()->comment('comma separated languages');
            $table->text('priorities')->after('language')->nullable()->comment('comma separated priorities');
            $table->enum('coaching_method', ['online', 'offline', 'both'])->after('priorities')->default('both');
            $table->integer('price_per_hour')->after('coaching_method')->default(20);
            $table->text('description')->after('price_per_hour')->nullable();
            /** Futher Coach Details End */

            /** Coach Company Details */
            $table->string('coach_company')->after('description')->nullable();
            $table->tinyInteger('is_commercial')->after('coach_company')->default(0);
            $table->enum('person_type', ['soletrader', 'business'])->after('is_commercial')->default('soletrader');
            $table->tinyInteger('small_business')->after('person_type')->default(0);
            $table->string('tax_number')->after('small_business')->nullable();
            $table->string('ust_id')->after('tax_number')->nullable();
            $table->string('company_type')->after('ust_id')->nullable();
            $table->string('company_number')->after('company_type')->nullable();
            $table->text('impressum')->after('company_number')->nullable();
            /** Coach Company Details End */
            
            /** Coach Uploaded files */
            $table->string('commercial_doc')->after('impressum')->nullable();
            $table->string('ustid_doc')->after('commercial_doc')->nullable();
            $table->string('avatar')->after('ustid_doc')->nullable();
            $table->string('banner')->after('avatar')->nullable();
            $table->string('video')->after('banner')->nullable();
            $table->string('id_doc')->after('video')->nullable();
            /** Coach Uploaded files End */


            /** Bank Details */
            $table->integer('bank_account_id')->after('id_doc')->nullable()->comment('Mangopay bank account id');
            $table->string('owner_name', 255)->after('bank_account_id')->comment('The name of the owner of the bank account')->nullable();
            $table->string('iban', 255)->after('owner_name')->comment('The IBAN of the bank account')->nullable();
            $table->string('bic', 255)->after('iban')->comment('The BIC of the bank account')->nullable();
            /** Bank Details End */

            /** Agreement Confirmations */
            $table->tinyInteger('agree_copyright')->after('bic')->default(0);
            $table->tinyInteger('agree_ustid')->after('agree_copyright')->default(0);
            $table->tinyInteger('terms_condition')->after('agree_ustid')->default(0);
            $table->tinyInteger('privacy_policy')->after('terms_condition')->default(0);
            $table->tinyInteger('agree_credentials')->after('privacy_policy')->default(0);
            /** Agreement Confirmations End */

            $table->enum('kyc_status', ['pending', 'asked', 'failed', 'validated' ])->after('agree_credentials')->default('pending');
            $table->text('kyc_message')->after('kyc_status')->nullable();
            $table->enum('ubo_status', ['pending', 'asked', 'incomplete', 'refused', 'validated'])->after('kyc_message')->default('pending');
            $table->text('ubo_message')->after('ubo_status')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('mango_user_id');
            $table->dropColumn('mango_wallet_id');
            $table->renameColumn('first_name', 'name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone_number');
            $table->dropColumn('birth_date');
            $table->dropColumn('nationality');
            $table->dropColumn('user_name');
            $table->dropColumn('is_anonymous');

            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('street');
            $table->dropColumn('house_no');
            $table->dropColumn('post_code');
            $table->dropColumn('place');
            $table->dropColumn('country');
            $table->dropColumn('country_code');
            $table->dropColumn('different_work');
            $table->dropColumn('work_latitude');
            $table->dropColumn('work_longitude');
            $table->dropColumn('work_street');
            $table->dropColumn('work_house_no');
            $table->dropColumn('work_post_code');
            $table->dropColumn('work_place');
            $table->dropColumn('work_country');
            $table->dropColumn('work_country_code');
            $table->dropColumn('show_on_map');

            $table->dropColumn('community');
            $table->dropColumn('language');
            $table->dropColumn('priorities');
            $table->dropColumn('coaching_method');
            $table->dropColumn('price_per_hour');
            $table->dropColumn('description');
            
            $table->dropColumn('coach_company');
            $table->dropColumn('person_type');
            $table->dropColumn('small_business');
            $table->dropColumn('tax_number');
            $table->dropColumn('ust_id');
            $table->dropColumn('company_type');
            $table->dropColumn('company_number');
            $table->dropColumn('impressum');

            $table->dropColumn('commercial_doc');
            $table->dropColumn('ustid_doc');
            $table->dropColumn('avatar');
            $table->dropColumn('banner');
            $table->dropColumn('video');
            $table->dropColumn('id_doc');

            $table->dropColumn('bank_account_id');
            $table->dropColumn('owner_name');
            $table->dropColumn('iban');
            $table->dropColumn('bic');
            $table->dropColumn('agree_copyright');
            $table->dropColumn('agree_ustid');
            $table->dropColumn('terms_condition');
            $table->dropColumn('privacy_policy');
            $table->dropColumn('agree_credentials');

            $table->dropColumn('kyc_status');
            $table->dropColumn('kyc_message');
            $table->dropColumn('ubo_status');
            $table->dropColumn('ubo_message');
            $table->dropSoftDeletes();
        });
    }
}
