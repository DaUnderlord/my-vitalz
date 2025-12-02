<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommunitiesSeeder extends Seeder
{
    public function run(): void
    {
        $communities = [
            [
                'name' => 'Diabetes Support',
                'slug' => 'diabetes-support',
                'description' => 'A supportive community for people living with diabetes. Share experiences, tips for blood sugar management, diet advice, and emotional support.',
                'category' => 'diabetes',
                'icon' => 'bx-droplet',
                'primary_color' => '#e74c3c',
                'is_featured' => true,
                'rules' => "1. Be respectful and supportive\n2. No medical advice - consult your doctor\n3. Share experiences, not diagnoses\n4. Protect your privacy\n5. Report inappropriate content",
            ],
            [
                'name' => 'Heart Health',
                'slug' => 'heart-health',
                'description' => 'Connect with others managing cardiovascular conditions. Discuss hypertension, heart disease prevention, lifestyle changes, and recovery journeys.',
                'category' => 'cardiovascular',
                'icon' => 'bx-heart',
                'primary_color' => '#e91e63',
                'is_featured' => true,
                'rules' => "1. Be kind and encouraging\n2. Share your journey, not medical advice\n3. Respect privacy\n4. No promotion of unverified treatments\n5. Support each other",
            ],
            [
                'name' => 'Mental Wellness',
                'slug' => 'mental-wellness',
                'description' => 'A safe space to discuss mental health, stress management, anxiety, depression, and overall emotional wellbeing. You are not alone.',
                'category' => 'mental_health',
                'icon' => 'bx-brain',
                'primary_color' => '#9c27b0',
                'is_featured' => true,
                'rules' => "1. This is a judgment-free zone\n2. Be compassionate\n3. If in crisis, contact emergency services\n4. No stigmatizing language\n5. Encourage professional help when needed",
            ],
            [
                'name' => 'Fitness & Exercise',
                'slug' => 'fitness-exercise',
                'description' => 'Share workout routines, fitness goals, exercise tips, and motivation. Whether you\'re just starting or a fitness enthusiast, everyone is welcome!',
                'category' => 'fitness',
                'icon' => 'bx-dumbbell',
                'primary_color' => '#4caf50',
                'is_featured' => false,
                'rules' => "1. Encourage, don't shame\n2. Share what works for you\n3. Consider individual limitations\n4. No extreme diet promotions\n5. Celebrate all progress",
            ],
            [
                'name' => 'Healthy Nutrition',
                'slug' => 'healthy-nutrition',
                'description' => 'Discuss healthy eating habits, meal planning, recipes, and nutritional advice. Learn how food impacts your health and wellbeing.',
                'category' => 'nutrition',
                'icon' => 'bx-bowl-rice',
                'primary_color' => '#ff9800',
                'is_featured' => false,
                'rules' => "1. No fad diet promotion\n2. Respect dietary choices\n3. Share recipes and tips\n4. Consider allergies when sharing\n5. Encourage balanced eating",
            ],
            [
                'name' => 'Weight Management',
                'slug' => 'weight-management',
                'description' => 'Support for those on their weight management journey. Share strategies, challenges, victories, and encouragement in a body-positive environment.',
                'category' => 'weight',
                'icon' => 'bx-body',
                'primary_color' => '#00bcd4',
                'is_featured' => false,
                'rules' => "1. Body positivity is key\n2. No shaming or judgment\n3. Share sustainable approaches\n4. Celebrate non-scale victories\n5. Support each other's goals",
            ],
            [
                'name' => 'Chronic Pain Warriors',
                'slug' => 'chronic-pain-warriors',
                'description' => 'A community for those living with chronic pain conditions. Share coping strategies, treatment experiences, and find understanding from others who truly get it.',
                'category' => 'chronic_pain',
                'icon' => 'bx-pulse',
                'primary_color' => '#607d8b',
                'is_featured' => false,
                'rules' => "1. Validate each other's experiences\n2. No pain comparisons\n3. Share what helps you cope\n4. Be patient and understanding\n5. Encourage professional care",
            ],
            [
                'name' => 'Women\'s Health',
                'slug' => 'womens-health',
                'description' => 'A dedicated space for women to discuss health topics including reproductive health, hormonal changes, pregnancy, menopause, and more.',
                'category' => 'womens_health',
                'icon' => 'bx-female',
                'primary_color' => '#e91e63',
                'is_featured' => true,
                'rules' => "1. Respect all experiences\n2. No judgment on personal choices\n3. Share knowledge supportively\n4. Protect privacy\n5. Encourage regular checkups",
            ],
            [
                'name' => 'Senior Health',
                'slug' => 'senior-health',
                'description' => 'A community for seniors and caregivers to discuss aging gracefully, managing age-related conditions, staying active, and maintaining quality of life.',
                'category' => 'senior',
                'icon' => 'bx-user-voice',
                'primary_color' => '#795548',
                'is_featured' => false,
                'rules' => "1. Respect wisdom and experience\n2. Be patient with technology questions\n3. Share practical tips\n4. Include caregivers in discussions\n5. Celebrate life at every age",
            ],
            [
                'name' => 'Sleep Better',
                'slug' => 'sleep-better',
                'description' => 'Struggling with sleep? Join others working on improving their sleep quality. Discuss insomnia, sleep hygiene, tips, and what works for better rest.',
                'category' => 'sleep',
                'icon' => 'bx-moon',
                'primary_color' => '#3f51b5',
                'is_featured' => false,
                'rules' => "1. Share sleep tips respectfully\n2. No promotion of sleep medications\n3. Discuss what works for you\n4. Be understanding of struggles\n5. Encourage healthy sleep habits",
            ],
        ];

        foreach ($communities as $community) {
            // Check if community already exists
            $exists = DB::table('communities')->where('slug', $community['slug'])->exists();
            if (!$exists) {
                DB::table('communities')->insert([
                    'name' => $community['name'],
                    'slug' => $community['slug'],
                    'description' => $community['description'],
                    'category' => $community['category'],
                    'icon' => $community['icon'],
                    'primary_color' => $community['primary_color'],
                    'is_public' => true,
                    'is_active' => true,
                    'is_featured' => $community['is_featured'],
                    'rules' => $community['rules'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
