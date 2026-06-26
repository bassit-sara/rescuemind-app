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
        'slug' => 'phq9',
        'title' => 'แบบประเมินภาวะซึมเศร้า (PHQ-9)',
        'description' => '9 คำถามมาตรฐานเพื่อประเมินระดับความรุนแรงของภาวะซึมเศร้า',
        'time_estimate' => '~3 นาที',
        'icon' => 'o-face-frown',
        'color_theme' => 'blue',
        'prop' => 'phq9Questions'
    ],
    [
        'slug' => 'gad7',
        'title' => 'แบบประเมินความวิตกกังวล (GAD-7)',
        'description' => '7 คำถามเพื่อตรวจวัดระดับความวิตกกังวลและความเครียดสะสม',
        'time_estimate' => '~3 นาที',
        'icon' => 'o-bolt',
        'color_theme' => 'emerald',
        'prop' => 'gad7Questions'
    ],
    [
        'slug' => 'ptsd',
        'title' => 'แบบประเมินความเครียดหลังเหตุการณ์ (PTSD)',
        'description' => 'ประเมินผลกระทบทางจิตใจหลังเผชิญเหตุการณ์วิกฤตหรือภัยพิบัติรุนแรง',
        'time_estimate' => '~5 นาที',
        'icon' => 'o-exclamation-triangle',
        'color_theme' => 'red',
        'prop' => 'ptsdQuestions'
    ],
    [
        'slug' => 'physical',
        'title' => 'แบบประเมินสุขภาพกายหลังภัยพิบัติ',
        'description' => 'ตรวจเช็คอาการบาดเจ็บ ความเสี่ยง และความต้องการทางโภชนาการเบื้องต้น',
        'time_estimate' => '~4 นาที',
        'icon' => 'o-heart',
        'color_theme' => 'emerald',
        'prop' => 'physicalQuestions'
    ],
    [
        'slug' => 'disaster_stress',
        'title' => 'ประเมินความเครียดจากภัยพิบัติ',
        'description' => '6 คำถามเจาะลึกผลกระทบด้านความกลัวและความวิตกกังวลที่เกิดขึ้นจากภัยพิบัติโดยตรง',
        'time_estimate' => '~4 นาที',
        'icon' => 'o-arrow-path',
        'color_theme' => 'orange',
        'prop' => 'disasterStressQuestions'
    ],
    [
        'slug' => 'burnout',
        'title' => 'ประเมินภาวะหมดไฟ (Burnout)',
        'description' => '5 คำถามประเมินระดับความเหนื่อยล้าทางอารมณ์และสภาวะหมดพลังในการก้าวข้ามปัญหา',
        'time_estimate' => '~3 นาที',
        'icon' => 'o-battery-0',
        'color_theme' => 'pink',
        'prop' => 'burnoutQuestions'
    ],
    [
        'slug' => 'sleep_quality',
        'title' => 'ประเมินคุณภาพการนอนหลับ',
        'description' => '5 คำถามสำรวจสภาวะการนอนไม่หลับ ปัญหาการนอนหลับยากสะสมหลังเผชิญวิกฤตภัยพิบัติ',
        'time_estimate' => '~3 นาที',
        'icon' => 'o-moon',
        'color_theme' => 'indigo',
        'prop' => 'sleepQualityQuestions'
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

echo "All hardcoded assessments migrated successfully!\n";
