<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'allow_self_registration' => 'boolean',
        'is_new_registered_user_active_by_default' => 'boolean',
        'use_captcha_on_registration' => 'boolean',
        'use_captcha_on_login' => 'boolean',
        'is_session_time_out_enabled' => 'boolean',
        'is_email_confirmation_required_for_login' => 'boolean',
        'is_cookie_consent_enabled' => 'boolean',
        'use_default_settings' => 'boolean',
        'require_digit' => 'boolean',
        'require_lowercase' => 'boolean',
        'require_non_alphanumeric' => 'boolean',
        'require_uppercase' => 'boolean',
        'is_email_provider_enabled' => 'boolean',
        'is_sms_provider_enabled' => 'boolean',
        'is_google_authenticator_enabled' => 'boolean',
        'is_remember_browser_enabled' => 'boolean',
        'smtp_enable_ssl' => 'boolean',
        'smtp_use_default_credentials' => 'boolean',
        'is_enabled' => 'boolean',
        'is_quick_theme_select_enabled' => 'boolean',
    ];
}
