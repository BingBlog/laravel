<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    /**
     * 处理template历史数据
     *
     * @return Response
     */
    public function run()
    {
        Template::chunk(10, function($templates)
        {
            foreach ($templates as $template)
            {
                $this->solveTemlapte($template);
            }
        });
    }

    private function solveTemlapte($template)
    {
        $model_id = $template->model_id;
        $content = json_decode($template->contents);
        $conditions = $content->conditions;
        $shouldUpdate = false;
        if (isset($conditions->mv)) {
            $shouldUpdate = true;
            $conditions->{'mv_'.$model_id} = $conditions->mv;
            unset($conditions->mv);
        }
        foreach ($conditions as $key => $condition) {
            if ($condition->t === 'dateRange') {
                if ($condition->s === 'everyDay') {
                    $condition->s = ['1'];
                } else {
                    $condition->s = [];
                }
                $conditions->{$key} = $condition;
            }
        }
        unset($content->conditions);
        $content->conditions = $conditions;
        $this->updateTemplate($template, json_encode($content));
    }

    private function updateTemplate($template, $json_encode_content)
    {
        $template->contents = $json_encode_content;
        $template->save();
    }
}