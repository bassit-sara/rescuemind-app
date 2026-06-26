<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new \App\Http\Controllers\MentalAssessmentController();
$reflection = new ReflectionClass($controller);

$forms = [
    [
        'slug' => 'disease_risk',
        'title' => 'ประเมินความเสี่ยงโรคติดต่อ',
        'description' => '6 คำถามสำรวจพฤติกรรมและความเสี่ยงต่อโรคระบาดที่มากับน้ำหรือหลังอุทกภัย',
        'time_estimate' => '~4 นาที',
        'icon' => 'o-bug-ant',
        'color_theme' => 'teal',
        'prop' => 'diseaseRiskQuestions'
    ],
    [
        'slug' => 'injury_severity',
        'title' => 'ประเมินความรุนแรงบาดแผล',
        'description' => '4 คำถามเพื่อแยกแยะระดับความรุนแรงของการบาดเจ็บว่าควรพบแพทย์ด่วนหรือไม่',
        'time_estimate' => '~2 นาที',
        'icon' => 'o-band-aid',
        'color_theme' => 'rose',
        'prop' => 'injurySeverityQuestions'
    ],
    [
        'slug' => 'nutrition_status',
        'title' => 'ประเมินภาวะขาดโภชนาการ',
        'description' => '4 คำถามเช็คความเสี่ยงจากการขาดแคลนอาหารและน้ำดื่มสะอาดในพื้นที่',
        'time_estimate' => '~2 นาที',
        'icon' => 'o-beaker',
        'color_theme' => 'amber',
        'prop' => 'nutritionStatusQuestions'
    ]
];

foreach ($forms as $form) {
    $prop = $reflection->getProperty($form['prop']);
    $prop->setAccessible(true);
    $questions = $prop->getValue($controller);

    \App\Models\CustomAssessment::updateOrCreate(
        ['slug' => $form['slug']],
        [
            'title' => $form['title'],
            'description' => $form['description'],
            'time_estimate' => $form['time_estimate'],
            'icon' => $form['icon'],
            'color_theme' => $form['color_theme'],
            'questions' => $questions,
            'is_active' => true,
        ]
    );
    echo "Migrated: " . $form['title'] . "\n";
}

echo "All physical assessments migrated successfully!\n";
