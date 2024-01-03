<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'color_scheme' => $this->color_scheme,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            // 'shadow_transparency' => $this->shadow_transparency,
            // 'primary_button' => $this->primary_button,
            // 'secondary_button' => $this->secondary_button,
            // 'footer_background_color' => $this->footer_background_color,
            // 'font' => $this->font,
            // 'header_color' => $this->header_color,
            // 'header_size' => $this->header_size,
            // 'breadcrumb' => $this->breadcrumb,
            // 'meta_tags_head' => $this->meta_tags_head,
            // 'meta_tags_body' => $this->meta_tags_body,
            // 'meta_tags_footer' => $this->meta_tags_footer,
            'application_name' => $this->application_name,
            'application_logo_image' => $this->application_logo_image,
            'dark_application_logo_image' => $this->dark_application_logo_image,
        ];
    }
}
