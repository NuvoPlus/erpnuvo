<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Module;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $all_modules = Module::whereIn('id', auth()->user()->getAllowedModulesForSystem())
            ->with(['levels' => function($query){
                $query->whereIn('id', [1,2,5,7,8,9,10]);
            }])
            ->orderBy('description')
            ->get();
        $modules_in_user = $this->modules->pluck('id')->toArray();
        $levels_in_user = $this->levels->pluck('id')->toArray();
        $modules = [];
        $levels = [];
        foreach ($all_modules as $module)
        {
            $modules[] = [
                'id' => $module->id,
                'description' => $module->description,
                'checked' => (bool) in_array($module->id, $modules_in_user)
            ];

            if(in_array($module->id, $modules_in_user)){

                foreach ($module->levels as $level) {
                    $levels[] = [
                        'id' => $level->id,
                        'description' => $level->description,
                        'checked' => (bool) in_array($level->id, $levels_in_user)
                    ];
                }

            }
        }
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'api_token' => $this->api_token,
            'establishment_id' => $this->establishment_id,
            'type' => $this->type,
            'modules' => $modules,
            'levels' => $levels,
            'locked' => (bool) $this->locked,
            'fe_resolution_id' => $this->fe_resolution_id,
            'nc_resolution_id' => $this->nc_resolution_id,
            'nd_resolution_id' => $this->nd_resolution_id,
            'ni_resolution_id' => $this->ni_resolution_id,
        ];
    }
}
