<?php

namespace App\Helpers;

use App\Models\ContentManagement;
use App\Models\RubricType;
use App\Models\Rubric;
use App\Models\Category;

if(!function_exists('default_rubric_types'))
{
    function default_rubric_types(){
        $type = new RubricType;
        $type->id = 1;
        $type->title_num = '0';
        $type->title = 'Funded';
        $type->save();

        $type2 = new RubricType;
        $type2->id = 2;
        $type2->title_num = '1';
        $type2->title = 'Not Funded';
        $type2->save();

        $type3 = new RubricType;
        $type3->id = 3;
        $type3->title_num = '2';
        $type3->title = 'Custom';
        $type3->save();
    }
}

if(!function_exists('default_categories'))
{
    function default_categories(){
        $category = new Category;
        $category->id = 1;
        $category->type_id = '1';
        $category->title = 'Quality';
        $category->save();

        $category2 = new Category;
        $category2->id = 2;
        $category2->type_id = '1';
        $category2->title = 'Impact';
        $category2->save();

        $category3 = new Category;
        $category3->id = 3;
        $category3->type_id = '1';
        $category3->title = 'Budget';
        $category3->save();

        $category4 = new Category;
        $category4->id = 4;
        $category4->type_id = '2';
        $category4->title = 'Quality';
        $category4->save();

        $category5 = new Category;
        $category5->id = 5;
        $category5->type_id = '2';
        $category5->title = 'Impact';
        $category5->save();
    }
}

if(!function_exists('default_rubric'))
{
    function default_rubric(){
        $rubricsDataforFunded = [
            ['rubric' => 'Qualifications of the Program/Project Leader and roles of Project Members',
            'category_id' => '1'],
            ['rubric' => 'Rationale',
            'category_id' => '1'],
            ['rubric' => 'Research Objectives',
            'category_id' => '1'],
            ['rubric' => 'Methodology',
            'category_id' => '1'],
            ['rubric' => 'Work plan',
            'category_id' => '1'],
            ['rubric' => 'Novelty/Impact to the discipline',
            'category_id' => '2'],
            ['rubric' => 'Project Deliverable',
            'category_id' => '2'],
            ['rubric' => 'Cost-effectiveness',
            'category_id' => '3'],
            ['rubric' => 'Compliance to prescribed line item budget guidelines',
            'category_id' => '3']
        ];

        foreach($rubricsDataforFunded as $r){
            $rubric = new Rubric;
            $rubric->rubric = $r['rubric'];
            $rubric->category_id = $r['category_id'];
            $rubric->save();
        }

        $rubricsDataforNotFunded = [
            ['rubric' => 'Qualifications of the Program/Project Leader and roles of Project Members',
            'category_id' => '4'],
            ['rubric' => 'Rationale',
            'category_id' => '4'],
            ['rubric' => 'Research Objectives',
            'category_id' => '4'],
            ['rubric' => 'Methodology',
            'category_id' => '4'],
            ['rubric' => 'Work plan',
            'category_id' => '4'],
            ['rubric' => 'Novelty/Impact to the discipline',
            'category_id' => '5'],
            ['rubric' => 'Project Deliverable',
            'category_id' => '5']
        ];

        foreach($rubricsDataforNotFunded as $r){
            $rubric = new Rubric;
            $rubric->rubric = $r['rubric'];
            $rubric->category_id = $r['category_id'];
            $rubric->save();
        }

    }
}
