<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::select(
            'color_scheme',
            'primary_color',
            'secondary_color',
            'shadow_transparency',
            'primary_button',
            'secondary_button',
            'footer_background_color',
            'font',
            'header_color',
            'header_size',
            'breadcrumb',
            'facebook',
            'twitter',
            'youtube',
            'instagram',
            'meta_tags_head',
            'meta_tags_body',
            'meta_tags_footer',
            'application_name',
            'application_logo_image',
            'mobile_logo_image',
            'dark_application_logo_image'
        )->first();
        return SettingResource::make($setting);
    }
}
