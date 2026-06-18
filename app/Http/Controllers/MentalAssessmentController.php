<?php

namespace App\Http\Controllers;

use App\Models\MentalAssessment;
use Illuminate\Http\Request;

class MentalAssessmentController extends Controller
{
    // PHQ-9 questions
    private array $phq9Questions = [
        'ไม่สนใจหรือไม่มีความสุขกับสิ่งต่างๆ',
        'รู้สึกหดหู่ เศร้า หรือสิ้นหวัง',
        'นอนหลับยากหรือหลับมากเกินไป',
        'รู้สึกเหนื่อยหรือมีพลังงานน้อย',
        'รับประทานอาหารน้อยลงหรือมากเกินไป',
        'รู้สึกไม่ดีกับตัวเอง หรือรู้สึกว่าตัวเองล้มเหลว',
        'มีปัญหาในการมีสมาธิ',
        'เคลื่อนไหวช้าหรือกระสับกระส่าย',
        'มีความคิดที่ว่าตายไปเสียดีกว่า หรือทำร้ายตัวเอง',
    ];

    // GAD-7 questions
    private array $gad7Questions = [
        'รู้สึกประหม่า วิตกกังวล หรือเครียด',
        'ไม่สามารถหยุดหรือควบคุมความกังวลได้',
        'กังวลมากเกินไปเรื่องต่างๆ',
        'มีปัญหาในการผ่อนคลาย',
        'กระสับกระส่ายจนนั่งอยู่เฉยๆ ไม่ได้',
        'หงุดหงิดง่ายหรือรู้สึกหัวร้อน',
        'รู้สึกกลัวว่าจะมีสิ่งเลวร้ายเกิดขึ้น',
    ];

    // PTSD questions (PCL-5 simplified)
    private array $ptsdQuestions = [
        'มีความทรงจำที่รบกวนจิตใจ ฝันร้ายเกี่ยวกับเหตุการณ์',
        'พยายามหลีกเลี่ยงความคิดหรือความรู้สึกที่เกี่ยวข้องกับเหตุการณ์',
        'หลีกเลี่ยงสิ่งที่เตือนให้นึกถึงเหตุการณ์',
        'มีความเชื่อแง่ลบเกี่ยวกับตัวเองหรือโลก',
        'รู้สึกผิดหรือโทษตัวเองเกี่ยวกับเหตุการณ์',
        'มีความรู้สึกด้านชาหรือห่างเหิน',
        'ไม่สนใจในกิจกรรมที่เคยชอบ',
        'มีอาการตื่นตัวมากผิดปกติ ตกใจง่าย',
    ];

    // Physical Assessment questions (disaster related)
    private array $physicalQuestions = [
        'มีบาดแผล รอยถลอก หรือรอยฟกช้ำตามร่างกายจากเหตุการณ์ภัยพิบัติ',
        'มีอาการปวดกล้ามเนื้อ ปวดข้อ หรือปวดเมื่อยจากการอพยพหรือขนย้ายสิ่งของ',
        'มีปัญหาเกี่ยวกับระบบทางเดินหายใจ เช่น ไอ หอบเหนื่อย จากฝุ่นควันหรือน้ำ',
        'มีอาการท้องเสีย คลื่นไส้ หรืออาหารเป็นพิษหลังจากเหตุการณ์',
        'มีอาการไข้ ปวดศีรษะ หรือรู้สึกครั่นเนื้อครั่นตัว',
        'มีปัญหาเกี่ยวกับผิวหนัง เช่น ผื่นคัน น้ำกัดเท้า หรือติดเชื้อที่ผิวหนัง',
        'รู้สึกอ่อนเพลียอย่างหนักหรือไม่มีแรงทำกิจกรรมประจำวัน',
    ];

    // Disaster Stress Assessment
    private array $disasterStressQuestions = [
        'รู้สึกหวาดกลัวหรือแพนิคทุกครั้งที่เห็นสัญญาณเตือนภัยหรือสภาพอากาศที่ย่ำแย่',
        'มีความกังวลเกี่ยวกับการสูญเสียทรัพย์สินหรือที่อยู่อาศัยจนรบกวนจิตใจ',
        'กังวลเรื่องความปลอดภัยของครอบครัวจนไม่สามารถทำกิจวัตรประจำวันได้',
        'รู้สึกสิ้นหวัง หรือรู้สึกว่าชีวิตสูญเสียความมั่นคงจากเหตุการณ์ภัยพิบัติ',
        'เสพข่าวสารภัยพิบัติหรือติดตามสถานการณ์ตลอดเวลาจนรู้สึกเครียดและปวดหัว',
        'มีอาการใจสั่น หายใจไม่เต็มอิ่ม หรือเหงื่อออกมากเมื่อนึกถึงภัยพิบัติ',
    ];

    // Communicable Disease Risk
    private array $diseaseRiskQuestions = [
        'มีอาการไข้สูง ปวดศีรษะ ปวดเมื่อยตามตัว หรือหนาวสั่น',
        'มีบาดแผลที่ต้องสัมผัสกับน้ำสกปรก หรือเคยเดินลุยน้ำย่ำโคลนโดยไม่ป้องกัน',
        'มีอาการท้องเสีย ถ่ายเหลว คลื่นไส้ หรืออาเจียนผิดปกติ',
        'มีอาการผิวหนังอักเสบ ผื่นแดง คัน หรือตุ่มน้ำใสขึ้นตามร่างกายหรือฝ่าเท้า',
        'มีอาการตาแดง เคืองตา หรือมีขี้ตามากผิดปกติ',
        'มีอาการไอต่อเนื่อง หอบเหนื่อย เจ็บคอ หรือหายใจลำบาก',
    ];

    // Burnout (Mental)
    private array $burnoutQuestions = [
        'รู้สึกเหนื่อยล้าทางอารมณ์และร่างกายจนไม่อยากทำอะไรเลย',
        'รู้สึกหงุดหงิดง่ายหรือหมดความอดทนกับคนรอบข้างมากกว่าปกติ',
        'รู้สึกว่าความพยายามในการจัดการปัญหาภัยพิบัตินี้สูญเปล่า',
        'รู้สึกอยากแยกตัวออกจากสังคม ไม่อยากพูดคุยกับใคร',
        'รู้สึกว่าตัวเองไม่สามารถรับมือกับปัญหาที่เข้ามาได้อีกต่อไป',
    ];

    // Sleep Quality (Mental)
    private array $sleepQualityQuestions = [
        'มีปัญหาในการนอนหลับหรือใช้เวลานานกว่าปกติจึงจะหลับได้',
        'ตื่นขึ้นมากลางดึกแล้วไม่สามารถหลับต่อได้',
        'ฝันร้ายเกี่ยวกับเหตุการณ์ภัยพิบัติจนสะดุ้งตื่น',
        'รู้สึกว่าตัวเองนอนหลับไม่สนิทและตื่นมาไม่สดชื่น',
        'อาการนอนไม่หลับส่งผลกระทบต่อการใช้ชีวิตประจำวัน',
    ];

    // Injury Severity (Physical)
    private array $injurySeverityQuestions = [
        'มีบาดแผลเปิดที่ยังมีเลือดออก หรือรอยช้ำขนาดใหญ่',
        'มีอาการเจ็บปวดรุนแรงที่กระดูก ข้อต่อ หรือกล้ามเนื้อจากการกระแทก',
        'มีอาการหน้ามืด วิงเวียนศีรษะ หรือเคยหมดสติหลังจากเกิดเหตุการณ์',
        'มีอาการชา หรืออ่อนแรงที่แขนขา ไม่สามารถขยับได้ตามปกติ',
        'บาดแผลที่มีอยู่เริ่มมีอาการปวด บวม แดง หรือมีหนอง',
    ];

    // Nutrition Status (Physical)
    private array $nutritionStatusQuestions = [
        'ต้องอดอาหารหรือลดปริมาณอาหารลงเนื่องจากอาหารไม่เพียงพอ',
        'รู้สึกอ่อนแรง วิงเวียน หน้ามืด เนื่องจากไม่ได้ทานอาหาร',
        'จำเป็นต้องดื่มน้ำที่สงสัยว่าไม่สะอาดหรือน้ำที่ไม่ได้ผ่านการฆ่าเชื้อ',
        'รู้สึกกระหายน้ำอย่างรุนแรงแต่ไม่มีน้ำดื่มที่ปลอดภัยเข้าถึงได้',
        'มีอาการปวดท้องหรืออาหารไม่ย่อยจากการทานอาหารแห้ง/อาหารบริจาคบ่อยๆ',
    ];

    public function create(string $type)
    {
        abort_unless(in_array($type, ['phq9', 'gad7', 'ptsd', 'physical', 'disaster_stress', 'disease_risk', 'burnout', 'sleep_quality', 'injury_severity', 'nutrition_status']), 404);
        $questions = match($type) {
            'phq9' => $this->phq9Questions,
            'gad7' => $this->gad7Questions,
            'ptsd' => $this->ptsdQuestions,
            'physical' => $this->physicalQuestions,
            'disaster_stress' => $this->disasterStressQuestions,
            'disease_risk' => $this->diseaseRiskQuestions,
            'burnout' => $this->burnoutQuestions,
            'sleep_quality' => $this->sleepQualityQuestions,
            'injury_severity' => $this->injurySeverityQuestions,
            'nutrition_status' => $this->nutritionStatusQuestions,
        };
        $titles = [
            'phq9' => 'แบบประเมินภาวะซึมเศร้า (PHQ-9)',
            'gad7' => 'แบบประเมินความวิตกกังวล (GAD-7)',
            'ptsd' => 'แบบประเมินความเครียดหลังเหตุการณ์ (PTSD)',
            'physical' => 'แบบประเมินสุขภาพกายหลังภัยพิบัติ',
            'disaster_stress' => 'แบบประเมินความเครียดจากภัยพิบัติ',
            'disease_risk' => 'แบบประเมินความเสี่ยงโรคติดต่อหลังภัยพิบัติ',
            'burnout' => 'แบบประเมินภาวะหมดไฟ (Burnout)',
            'sleep_quality' => 'แบบประเมินคุณภาพการนอนหลับ (Insomnia)',
            'injury_severity' => 'แบบประเมินความรุนแรงของการบาดเจ็บ',
            'nutrition_status' => 'แบบประเมินภาวะขาดแคลนอาหารและน้ำ',
        ];
        $title = $titles[$type];
        return view('mental.assess', compact('type', 'questions', 'title'));
    }

    public function store(Request $request)
    {
        $type = $request->type;
        abort_unless(in_array($type, ['phq9', 'gad7', 'ptsd', 'physical', 'disaster_stress', 'disease_risk', 'burnout', 'sleep_quality', 'injury_severity', 'nutrition_status']), 422);

        $request->validate([
            'type'      => 'required|in:phq9,gad7,ptsd,physical,disaster_stress,disease_risk,burnout,sleep_quality,injury_severity,nutrition_status',
            'answers'   => 'required|array',
            'answers.*' => 'required|integer|min:0|max:3',
        ]);

        $score = array_sum($request->answers);
        $severity = $this->calculateSeverity($type, $score);

        $assessment = MentalAssessment::create([
            'user_id'  => auth()->id(),
            'type'     => $type,
            'answers'  => $request->answers,
            'score'    => $score,
            'severity' => $severity,
        ]);

        return redirect()->route('mental.assess.show', $assessment)->with('success', 'ทำแบบประเมินสำเร็จ');
    }

    public function show(MentalAssessment $mentalAssessment)
    {
        abort_unless($mentalAssessment->user_id === auth()->id(), 403);
        return view('mental.result', compact('mentalAssessment'));
    }

    public function officerIndex()
    {
        $assessments = MentalAssessment::with('user')
            ->where('severity', '!=', 'minimal')
            ->latest()
            ->paginate(20);
        return view('mental-officer.assessments', compact('assessments'));
    }

    public function officerShow(MentalAssessment $mentalAssessment)
    {
        return view('mental-officer.assessment-show', compact('mentalAssessment'));
    }

    private function calculateSeverity(string $type, int $score): string
    {
        return match($type) {
            'phq9' => match(true) {
                $score <= 4  => 'minimal',
                $score <= 9  => 'mild',
                $score <= 14 => 'moderate',
                default      => 'severe',
            },
            'gad7' => match(true) {
                $score <= 4  => 'minimal',
                $score <= 9  => 'mild',
                $score <= 14 => 'moderate',
                default      => 'severe',
            },
            'ptsd' => match(true) {
                $score <= 10 => 'minimal',
                $score <= 20 => 'mild',
                $score <= 30 => 'moderate',
                default      => 'severe',
            },
            'physical' => match(true) {
                $score <= 4  => 'minimal',
                $score <= 9  => 'mild',
                $score <= 14 => 'moderate',
                default      => 'severe',
            },
            'disaster_stress' => match(true) {
                $score <= 4  => 'minimal',
                $score <= 9  => 'mild',
                $score <= 14 => 'moderate',
                default      => 'severe',
            },
            'disease_risk' => match(true) {
                $score <= 3  => 'minimal',
                $score <= 7  => 'mild',
                $score <= 12 => 'moderate',
                default      => 'severe',
            },
            'burnout' => match(true) {
                $score <= 3  => 'minimal',
                $score <= 7  => 'mild',
                $score <= 11 => 'moderate',
                default      => 'severe',
            },
            'sleep_quality' => match(true) {
                $score <= 3  => 'minimal',
                $score <= 7  => 'mild',
                $score <= 11 => 'moderate',
                default      => 'severe',
            },
            'injury_severity' => match(true) {
                $score <= 2  => 'minimal',
                $score <= 5  => 'mild',
                $score <= 9  => 'moderate',
                default      => 'severe',
            },
            'nutrition_status' => match(true) {
                $score <= 2  => 'minimal',
                $score <= 5  => 'mild',
                $score <= 9  => 'moderate',
                default      => 'severe',
            },
            default => 'minimal',
        };
    }
}
