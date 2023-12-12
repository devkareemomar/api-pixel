<?php

namespace App\Http\Resources;

use App\Models\CountryProject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $countries = [];

        if ($this->countries) {
            foreach ($this->countries as $country) {

                $countryProject = CountryProject::query()
                    ->select('suggested_values', 'country_id', 'id')
                    ->where('project_id', $this->id)
                    ->where('country_id', $country->id)
                    ->get();
                $countries[] = [
                    'id' => $country->id,
                    'name' => $country->name,
                    'short_name' => $country->short_name,
                    'flag' => config('app.dashboard') . $country->flag,
                    'amount_variants' => $countryProject ? $countryProject->pluck('share_value')->toArray() : null,
                ];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name ?? $this->project_name,
            'sku' => $this->sku,
            'slug' => $this->slug ?? $this->project_slug,
            'status' => $this->status,
            'short_description' => $this->short_description ?? $this->project_short_description,
            'description' => $this->description ?? $this->project_description,
            'total_earned' => number_format((float)$this->total_earned, 0, '.', ','),
            'total_collected' => number_format((float)$this->total_collected, 0, '.', ','),
            'total_wanted' => number_format((float)$this->total_wanted, 0, '.', ','),
            'earned_percentage' => min($this->earned_percentage, 100),
            'total_remains' => $this->total_remains > 0 ? number_format((float)$this->total_remains, 0, '.', ',') : 0,
            'thumbnail' => $this->thumbnail ? config('app.dashboard') . $this->thumbnail : null,
            'show_in_home_page' => (int)$this->show_in_home_page,
            'show_in_banner' => (int)$this->show_in_banner,
            'featured' => (int)$this->featured,
            'start_date' => optional($this->start_date)->format('Y . m . d'),
            'end_date' => optional($this->end_date)->format('Y . m . d'),
            'visibility' => (int)$this->visibility,
            'accept_donation' => (int)$this->accept_donation,
            'show_in_shop' => (int)$this->show_in_shop,
            'is_gift' => (int)$this->is_gift,
            'show_in_menu' => (int)$this->show_in_menu,
            'hidden' => (int)$this->hidden,
            'donation_available' => $this->donation_available,
            'show_donation_comment' => (int)$this->show_donation_comment,
            'donation_comment' => $this->donation_comment,
            'is_zakat' => (int)$this->is_zakat,
            'show_donor_phone' => (int)$this->show_donor_phone,
            'donor_phone_required' => (int)$this->donor_phone_required,
            'show_donor_name' => (int)$this->show_donor_name,
            'donor_name_required' => (int)$this->donor_name_required,
            'show_banner' => (int)$this->show_banner,
            'is_continuous' => (int)$this->is_continuous,
            'is_full_unit' => (int)$this->is_full_unit,
            'is_stock' => $this->is_stock,
            'is_quick_donation' => (int)$this->is_quick_donation,
            'unit_value' => $this->unit_value,
            'stock' => $this->stock,
            'show_timer' => (int)$this->show_timer,
            'show_target_amount' => (int)$this->show_target_amount,
            'show_paid_amount' => (int)$this->show_paid_amount,
            'show_percentage' => (int)$this->show_percentage,
            'main_image' => $this->main_image ? config('app.dashboard') . $this->main_image : null,
            'video' => $this->video,
            'banner_image' => $this->banner_image ? config('app.dashboard') . $this->banner_image : null,
            'created_at' => $this->created_at?->format('Y . m . d'),
            'translation' => $this->getAllTranslation(),
            'is_multi_country' => (int)$this->is_multi_country ? true : false,
            'highlighted' => $this->highlighted ?? false,
            'images' => ImageResource::collection($this->images),
            'category' => $this->category,
            'album' => $this->album,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'languages' => $this->languages,
            'visits' => $this->visits()->count(),
            'countries' => $countries,
            'country' => $this->country,
            'albums' => AlbumResource::collection($this->albums),
            'suggested_values' => $this->suggested_values,
            'related_projects' => $this->getRelatedProjects()->map(function ($item) {
                $item->thumbnail = config('app.dashboard') . $item->thumbnail;
                return $item;
            }),
//            'languageProjects' => $this->languageProjects
        ];
    }
}
