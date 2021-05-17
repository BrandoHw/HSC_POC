<?php

namespace App\Services;

class PermissionService
{
    public function organise_permissions($permissions)
    {
        $holder = "";

        $modules= collect();
        $controls = collect();
        
        foreach ($permissions as $permission){
            
            $module = explode('-', $permission['name'])[0];

            if($module == "company"){
                $module = "client";
            }

            $control = explode('-', $permission['name'])[1];
            
            $controls->push(collect([
                'id' => $permission['id'],
                'module' => $module,
                'control' => $control,
            ]));

            if($module != $holder){
                $modules->push(collect([
                    'name' => $module,
                    'list' => null,
                    'create' => null,
                    'edit' => null,
                    'delete' => null,
                ]));
                $holder = $module;
            }
        }

        foreach ($modules as $module){
            $filtered = $controls->where('module', $module['name'])->values();
            $holder = $module;

            foreach($filtered as $filter){
                switch($filter['control']){
                    case 'list':
                        $module['list'] = $filter['id'];
                        break;
                    case 'create':
                        $module['create'] = $filter['id'];
                        break;
                    case 'edit':
                        $module['edit'] = $filter['id'];
                        break;
                    case 'delete':
                        $module['delete'] = $filter['id'];
                        break;
                }
            }
        }

        return $modules;
    }
}
