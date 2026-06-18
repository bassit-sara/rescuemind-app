<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SosRequestController;
use App\Http\Controllers\MissingPersonController;
use App\Http\Controllers\HazardReportController;
use App\Http\Controllers\AiCompanionController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ReliefPointController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\MentalAssessmentController;
use App\Http\Controllers\MoodLogController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\OfficerDashboardController;
use App\Http\Controllers\MentalDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\FamilySafeController;
use App\Http\Controllers\PreparednessController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    $alerts = \App\Models\Alert::where('is_active', true)->orderByDesc('level')->take(5)->get();
    $reliefPoints = \App\Models\ReliefPoint::where('is_active', true)->count();
    $totalSos = \App\Models\SosRequest::count();
    $resolvedSos = \App\Models\SosRequest::where('status', 'safe')->count();
    return view('welcome', compact('alerts', 'reliefPoints', 'totalSos', 'resolvedSos'));
})->name('home');

Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
Route::get('/alerts/{alert}', [AlertController::class, 'show'])->name('alerts.show');
Route::get('/relief-points', [ReliefPointController::class, 'index'])->name('relief-points.index');
Route::get('/preparedness', [PreparednessController::class, 'index'])->name('preparedness.index');
Route::get('/how-to-use', function () {
    return view('how-to-use');
})->name('how-to-use');

// News (public)
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

// Landing Pages for 3 Dimensions
Route::get('/mt1', function () {
    if (auth()->check()) return redirect()->route('alerts.index');
    return view('pages.mt1');
})->name('mt1');

Route::get('/mt2', function () {
    if (auth()->check()) return redirect()->route('sos.create');
    return view('pages.mt2');
})->name('mt2');

Route::get('/mt3', function () {
    if (auth()->check()) return redirect()->route('mental.index');
    return view('pages.mt3');
})->name('mt3');

// Privacy Policy
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy.policy');

// ============================================================
// AUTH ROUTES (user role+)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) return redirect()->route('super-admin.dashboard');
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('officer')) return redirect()->route('officer.dashboard');
        if ($user->hasRole('mental_officer')) return redirect()->route('mental-officer.dashboard');
        if ($user->hasRole('volunteer')) return redirect()->route('volunteer.dashboard');
        return redirect()->route('home');
    })->name('dashboard');



    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // News comments (auth required)
    Route::post('/news/{news}/comment', [NewsController::class, 'comment'])->name('news.comment');
    Route::delete('/news/comment/{comment}', [NewsController::class, 'deleteComment'])->name('news.comment.destroy');

    // SOS
    Route::get('/sos', [SosRequestController::class, 'create'])->name('sos.create');
    Route::post('/sos', [SosRequestController::class, 'store'])->name('sos.store');
    Route::get('/sos/my', [SosRequestController::class, 'myRequests'])->name('sos.my');
    Route::get('/sos/{sosRequest}', [SosRequestController::class, 'show'])->name('sos.show');

    // Missing Person
    Route::resource('missing-persons', MissingPersonController::class)->only(['index', 'create', 'store', 'show']);

    // Hazard Report
    Route::resource('hazard-reports', HazardReportController::class)->only(['index', 'create', 'store', 'show']);

    // AI Companion Chat
    Route::post('/ai-companion/chat', [AiCompanionController::class, 'chat'])->name('ai-companion.chat');

    // Family Safe Check
    Route::post('/family/safe', [FamilySafeController::class, 'markSafe'])->name('family.safe');
    Route::get('/family/status', [FamilySafeController::class, 'index'])->name('family.status');

    // Mental Health
    Route::prefix('mental')->name('mental.')->group(function () {
        Route::get('/', function () {
            $user = auth()->user();
            $assessments = $user->mentalAssessments()->latest()->take(5)->get();
            $moods = $user->moodLogs()->latest()->take(30)->get();
            return view('mental.index', compact('assessments', 'moods'));
        })->name('index');

        Route::get('/assess/{type}', [MentalAssessmentController::class, 'create'])->name('assess.create');
        Route::post('/assess', [MentalAssessmentController::class, 'store'])->name('assess.store');
        Route::get('/result/{mentalAssessment}', [MentalAssessmentController::class, 'show'])->name('assess.show');

        Route::get('/mood', [MoodLogController::class, 'index'])->name('mood.index');
        Route::post('/mood', [MoodLogController::class, 'store'])->name('mood.store');

        Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
        Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');
        Route::patch('/journal/{journal}', [JournalController::class, 'update'])->name('journal.update');
        Route::delete('/journal/{journal}', [JournalController::class, 'destroy'])->name('journal.destroy');

        Route::resource('appointments', AppointmentController::class)->only(['index', 'create', 'store', 'show']);
        
        $getArticles = function () {
            return [
                [
                    'id' => 1,
                    'category' => 'mental',
                    'title' => 'วิธีจัดการกับความเครียดและตื่นตระหนก (Panic) หลังเกิดภัยพิบัติ',
                    'desc' => 'เข้าใจอาการตื่นตระหนกและรับมือด้วยหลักการ 5-4-3-2-1 Grounding Technique เพื่อดึงสติกลับมาอยู่กับปัจจุบันและผ่อนคลายกล้ามเนื้ออย่างเป็นธรรมชาติ',
                    'read_time' => '5 นาที',
                    'author' => 'นพ. วรุตม์ (จิตแพทย์อาสา)',
                    'icon' => '🧘‍♀️',
                    'video_url' => 'https://www.youtube.com/embed/5Dqj92A5b_8',
                    'content' => '
                        <p class="mb-4">หลังเกิดภัยพิบัติ ร่างกายและจิตใจของท่านอาจยังอยู่ในสภาวะตื่นตัวสูง (Fight or Flight) ซึ่งส่งผลให้บางครั้งเกิดอาการตื่นตระหนก หายใจไม่อิ่ม แน่นหน้าอก หรือหัวใจเต้นเร็วผิดปกติโดยไม่มีสาเหตุทางกายภาพ</p>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">เทคนิคการดึงสติกลับมาด้วยประสาทสัมผัสทั้ง 5 (5-4-3-2-1 Grounding Technique)</h4>
                        <p class="mb-4">เมื่อรู้สึกเริ่มควบคุมตัวเองไม่ได้ ให้กวาดสายตาและพยายามรับรู้สิ่งรอบตัวตามขั้นตอนดังนี้:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4 text-gray-600 dark:text-gray-300">
                            <li><strong class="text-purple-600">5 สิ่งที่มองเห็น:</strong> สังเกตสิ่งของ 5 อย่างรอบตัว เช่น โต๊ะ เก้าอี้ แก้วน้ำ เสื้อผ้า หรือใบไม้</li>
                            <li><strong class="text-purple-600">4 สิ่งที่สัมผัสได้:</strong> รับรู้ความรู้สึกทางผิวสัมผัส 4 อย่าง เช่น ฝ่าเท้ากระทบพื้น เสื้อผ้าที่สวมใส่ ลมพัดผ่านหน้า หรือการจับแก้วน้ำอุ่น</li>
                            <li><strong class="text-purple-650">3 สิ่งที่ได้ยิน:</strong> ตั้งใจฟังเสียง 3 เสียงที่ดังอยู่ เช่น เสียงลมพัด เสียงนกร้อง เสียงพูดคุย หรือเสียงเครื่องยนต์</li>
                            <li><strong class="text-purple-655">2 สิ่งที่ได้กลิ่น:</strong> สูดกลิ่นรอบตัว 2 อย่าง เช่น กลิ่นสบู่ กลิ่นชา/กาแฟ หรือกลิ่นดินหลังฝนตก</li>
                            <li><strong class="text-purple-660">1 สิ่งที่รับรส:</strong> รับรสสัมผัส 1 อย่างในปาก เช่น รสสัมผัสของยาสีฟัน การจิบน้ำสะอาด หรือการอมลูกอม</li>
                        </ul>
                        <p class="mb-4"><strong>สรุป:</strong> การทำสมาธิแบบ Grounding นี้จะส่งสัญญาณให้สมองส่วนควบคุมความกลัวรับรู้ว่า "ในขณะนี้ท่านอยู่ในพื้นที่ปลอดภัยแล้ว" ส่งผลให้อัตราการเต้นของหัวใจและการหายใจกลับมาเป็นปกติใน 3-5 นาที</p>
                    '
                ],
                [
                    'id' => 2,
                    'category' => 'physical',
                    'title' => 'การดูแลบาดแผลเบื้องต้นและป้องกันการติดเชื้อจากน้ำสกปรก',
                    'desc' => 'ขั้นตอนปฏิบัติทันทีเมื่อเกิดแผลสัมผัสน้ำท่วมขังขุ่นมัว การล้างแผลด้วยน้ำสะอาดและยาฆ่าเชื้อ ตลอดจนสัญญาณเตือนแผลติดเชื้อรุนแรงที่ต้องพบแพทย์',
                    'read_time' => '4 นาที',
                    'author' => 'พญ. นลินี (แพทย์เวชศาสตร์ทั่วไป)',
                    'icon' => '🩹',
                    'video_url' => 'https://www.youtube.com/embed/2_WlV3KqGaw',
                    'content' => '
                        <p class="mb-4">น้ำท่วมขังมักเต็มไปด้วยเชื้อแบคทีเรีย สิ่งปฏิกูล และสิ่งมีชีวิตขนาดเล็ก หากผิวหนังมีบาดแผล รอยถลอก หรือรอยขีดข่วน ต้องรีบทำความสะอาดและดูแลรักษาอย่างถูกวิธีทันที เพื่อหลีกเลี่ยงการติดเชื้อในกระแสเลือดหรือภาวะแทรกซ้อนรุนแรง</p>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">ขั้นตอนการปฐมพยาบาลแผลเบื้องต้นเมื่อสัมผัสน้ำสกปรก</h4>
                        <ol class="list-decimal list-inside space-y-2 mb-4 text-gray-600 dark:text-gray-300">
                            <li><strong>ล้างแผลทันที:</strong> ใช้น้ำสะอาดปริมาณมากและสบู่ล้างทำความสะอาดแผลอย่างระมัดระวัง พยายามอย่าล้างแผลด้วยน้ำท่วมขังโดยเด็ดขาด</li>
                            <li><strong>เช็ดแผลให้แห้ง:</strong> ใช้ผ้าสะอาดที่ผ่านการฆ่าเชื้อแล้วหรือสำลีสะอาดซับแผลให้แห้งสนิท</li>
                            <li><strong>ใส่ยาฆ่าเชื้อ:</strong> ทาเบตาดีน (Povidone-Iodine) หรือน้ำยาล้างแผลที่มีฤทธิ์ฆ่าเชื้อเพื่อจำกัดแบคทีเรีย</li>
                            <li><strong>ปิดแผล:</strong> ใช้พลาสเตอร์ยาชนิดกันน้ำปิดแผลไว้เพื่อหลีกเลี่ยงการสัมผัสน้ำท่วมขังขุ่นมัวครั้งใหม่</li>
                        </ol>
                        <h4 class="font-bold text-red-600 dark:text-red-400 mb-2 text-base">🚨 สัญญาณเตือนแผลติดเชื้อ (ต้องไปพบแพทย์ด่วน)</h4>
                        <p class="mb-4">หากแผลเริ่มมีอาการบวมแดง รู้สึกร้อนบริเวณรอบแผล มีหนองซึม มีไข้ขึ้น หรือเริ่มมีขีดแดงเป็นเส้นวิ่งลามจากแผลไปตามท่อน้ำเหลืองหรือเส้นเลือด ให้รีบนำตัวส่งโรงพยาบาลหรือจุดปฐมพยาบาลที่ใกล้ที่สุดทันที</p>
                    '
                ],
                [
                    'id' => 3,
                    'category' => 'prevention',
                    'title' => 'โรคติดต่อทางน้ำที่มากับน้ำท่วมและการป้องกันตัวเอง',
                    'desc' => 'ทำความรู้จักกับโรคฉี่หนู (Leptospirosis) อหิวาตกโรค และตาแดง วิธีหลีกเลี่ยงการสัมผัสน้ำท่วมขัง และการเลือกกินอาหารน้ำดื่มที่ปลอดภัย',
                    'read_time' => '6 นาที',
                    'author' => 'ดร. กิตติ (ผู้เชี่ยวชาญระบาดวิทยา)',
                    'icon' => '🦠',
                    'content' => '
                        <p class="mb-4">สภาวะน้ำท่วมขังสร้างสภาพแวดล้อมที่เหมาะสมต่อการแพร่พันธุ์ของเชื้อโรคหลายประเภท การตระหนักรู้และเตรียมพร้อมป้องกันตนเองเป็นหัวใจสำคัญในการรักษาความปลอดภัยทางสุขภาพของคนในครอบครัว</p>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">3 โรคระบาดที่ต้องระวังเป็นพิเศษ</h4>
                        <ul class="list-disc list-inside space-y-2 mb-4 text-gray-600 dark:text-gray-300">
                            <li><strong>โรคเลปโตสไปโรซิส (โรคฉี่หนู):</strong> ติดต่อผ่านแผลหรือเยื่อบุตา สัมผัสปัสสาวะของหนูหรือสัตว์นำโรคในน้ำขัง อาการคือไข้สูงเฉียบพลัน ปวดน่องรุนแรง ตาแดง และปวดศีรษะ</li>
                            <li><strong>โรคอหิวาตกโรค / ท้องร่วงรุนแรง:</strong> เกิดจากการดื่มน้ำหรืออาหารที่ปนเปื้อนเชื้ออุจจาระหรือสิ่งปฏิกูล อาการคือถ่ายเหลวเป็นน้ำปริมาณมาก อ่อนเพลียรุนแรง</li>
                            <li><strong>โรคตาแดง:</strong> เกิดจากน้ำสกปรกกระเด็นเข้าตา หรือใช้มือสกปรกขยี้ตา ทำให้ตาแดงเคือง มีขี้ตามาก</li>
                        </ul>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">วิธีป้องกันตัวเองอย่างมีประสิทธิภาพ</h4>
                        <p class="mb-4">
                            1. สวมรองเท้าบูทยางและถุงมือยางทุกครั้งหากต้องเดินลุยน้ำขัง<br>
                            2. ล้างมือด้วยสบู่ทันทีหลังการลุยน้ำหรือก่อนรับประทานอาหาร<br>
                            3. ดื่มน้ำที่ผ่านการกรองและต้มสุก หรือน้ำดื่มบรรจุขวดที่ปิดสนิทเท่านั้น<br>
                            4. หลีกเลี่ยงการขยี้ตาหรือสัมผัสใบหน้าด้วยมือที่ยังไม่สะอาด
                        </p>
                    '
                ],
                [
                    'id' => 4,
                    'category' => 'mental',
                    'title' => 'การสังเกตและดูแลจิตใจผู้สูงอายุในสภาวะลี้ภัยน้ำท่วม',
                    'desc' => 'ผู้สูงอายุมักมีความเครียดแฝงจากการเปลี่ยนสถานที่และสูญเสียทรัพย์สิน วิธีสังเกตอาการซึมเศร้าเงียบๆ และแนวทางการพูดคุยให้สติและปลอบโยนใจ',
                    'read_time' => '7 นาที',
                    'author' => 'คุณมณี (นักจิตวิทยาคลินิก)',
                    'icon' => '👵',
                    'content' => '
                        <p class="mb-4">ผู้สูงอายุเป็นกลุ่มเปถามสูงเมื่อเกิดภัยพิบัติ เนื่องจากมักมีความผูกพันกับบ้านสูงและอาจรู้สึกหมดหวัง ท้อแท้ใจ หรือกังวลว่าจะเป็นภาระของลูกหลานในพื้นที่อพยพ</p>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">วิธีสังเกตอาการเครียดแฝงในผู้สูงอายุ</h4>
                        <ul class="list-disc list-inside space-y-2 mb-4 text-gray-600 dark:text-gray-300">
                            <li><strong>มีอาการเก็บตัว:</strong> ไม่ยอมพูดคุย นั่งเหม่อลอย ปฏิเสธการร่วมกิจกรรม</li>
                            <li><strong>เบื่ออาหารฉับพลัน:</strong> ทานอาหารได้น้อยลงมาก หรือปฏิเสธการทานอาหาร</li>
                            <li><strong>นอนไม่หลับสะสม:</strong> นอนสะดุ้ง ฝันร้าย นอนไม่หลับตลอดคืน</li>
                            <li><strong>บ่นเรื่องอาการทางกาย:</strong> เช่น ปวดหัว แน่นหน้าอก ปวดท้องบ่อยๆ โดยไม่พบโรคทางกายเด่นชัด</li>
                        </ul>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">แนวทางการดูแลและปลอบโยนใจ</h4>
                        <p class="mb-4">
                            - <strong>รับฟังด้วยความเข้าใจ:</strong> ให้เวลาท่านเล่าความรู้สึก ความเสียดายบ้าน หรือของสะสม โดยไม่ขัดจังหวะ<br>
                            - <strong>มอบบทบาทที่เหมาะสม:</strong> ให้ช่วยงานเบาๆ ในศูนย์ลี้ภัย เช่น การช่วยคัดแยกขวดน้ำ เพื่อให้รู้สึกมีคุณค่า<br>
                            - <strong>จำกัดข่าวสารดราม่า:</strong> หลีกเลี่ยงการเปิดทีวีดูข่าวน้ำท่วมวนไปมา เพื่อลดความกระวนกระวายใจ
                        </p>
                    '
                ],
                [
                    'id' => 5,
                    'category' => 'physical',
                    'title' => 'สภาวะขาดน้ำในพื้นที่อพยพ: อาการและแนวทางรักษาเบื้องต้น',
                    'desc' => 'วิธีการทำผงเกลือแร่ (ORS) ดื่มเองด้วยวัตถุดิบในครัวเรือน (เกลือและน้ำตาล) เมื่ออยู่ในสภาวะขาดแคลนยารักษาโรคในพื้นที่ห่างไกล',
                    'read_time' => '3 นาที',
                    'author' => 'นพ. สมชาย (กู้ชีพนเรนทร)',
                    'icon' => '🥤',
                    'content' => '
                        <p class="mb-4">การสูญเสียน้ำในร่างกายอย่างรวดเร็ว (จากการสูญเสียเหงื่อจากความร้อนจัด หรือจากโรคท้องร่วงเฉียบพลัน) สามารถส่งผลให้ความดันโลหิตลดต่ำและเกิดภาวะช็อกที่เป็นอันตรายถึงชีวิตได้</p>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">สูตรเตรียมน้ำเกลือแร่ฉุกเฉิน (Home-made ORS)</h4>
                        <p class="mb-4">หากไม่มีผงเกลือแร่บรรจุซอง สามารถเตรียมน้ำเกลือแร่ใช้เองได้ทันทีด้วยวัตถุดิบในครัวเรือนดังนี้:</p>
                        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-150 rounded-2xl p-4 mb-4 text-sm">
                            - <strong>น้ำต้มสุกที่เย็นแล้ว:</strong> ปริมาตร 750 มล. (ขวดน้ำขนาดกลางประมาณ 1 ขวดครึ่ง)<br>
                            - <strong>เกลือป่น:</strong> ครึ่งช้อนชา (ชดเชยโซเดียม)<br>
                            - <strong>น้ำตาลทราย:</strong> 2 ช้อนโต๊ะ (ให้พลังงานและช่วยการดูดซึมเกลือแร่)<br>
                            - <strong>วิธีทำ:</strong> คนผสมให้ละลายเข้ากันดี จิบบ่อยๆ ตลอดวันเพื่อทดแทนน้ำที่เสียไป
                        </div>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">ระดับอาการขาดน้ำที่ต้องส่งแพทย์ด่วน</h4>
                        <p class="mb-4">ปัสสาวะมีสีเหลืองเข้มจัดหรือไม่มีปัสสาวะเลยเกิน 6 ชั่วโมง, ริมฝีปากแห้ง แตก ผิวหนังเหี่ยวเมื่อหยิกแล้วไม่คืนตัวทันที, รู้สึกเวียนหัว หน้ามืดเมื่อขยับตัว หรือชีพจรเต้นเร็วและเบา</p>
                    '
                ],
                [
                    'id' => 6,
                    'category' => 'mental',
                    'title' => 'ก้าวผ่านภาวะหมดไฟและท้อแท้ใจ (Burnout) ในช่วงฟื้นฟูหลังวิกฤต',
                    'desc' => 'เมื่อน้ำลดแต่ความเหนื่อยล้ายังคงอยู่ เรียนรู้วิธีแบ่งเป้าหมายการฟื้นฟูบ้านและชีวิตออกเป็นขั้นตอนเล็กๆ และการอนุญาตให้ตัวเองหยุดพักและร้องขอความช่วยเหลือ',
                    'read_time' => '5 นาที',
                    'author' => 'คุณพิมพรรณ (ที่ปรึกษาสุขภาพจิต)',
                    'icon' => '🕯️',
                    'content' => '
                        <p class="mb-4">ความท้อแท้เหนื่อยล้าสะสมมักเกิดขึ้นเด่นชัดในช่วงน้ำลด เนื่องจากผู้ประสบภัยต้องเผชิญกับสภาพความเสียหายที่แท้จริงของบ้านเรือนและการจัดการกับโคลนตมจำนวนมาก พร้อมความเครียดด้านการเงิน</p>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-2 text-base">แนวทางการดูแลและกอบกู้พลังใจกลับคืนมา</h4>
                        <ol class="list-decimal list-inside space-y-2 mb-4 text-gray-600 dark:text-gray-300">
                            <li><strong>แบ่งงานเป็นเป้าหมายเล็กๆ:</strong> หลีกเลี่ยงความรู้สึกท้อใจจากการมองงานภาพใหญ่ ค่อยๆ แบ่งเขตการทำความสะอาดบ้านทีละห้อง ทำเสร็จหนึ่งจุดให้ชื่นชมและให้กำลังใจตัวเอง</li>
                            <li><strong>ยอมรับอารมณ์ของตนเอง:</strong> อนุญาตให้ตัวเองรู้สึกเหนื่อย ท้อ หรือร้องไห้ได้ ความอ่อนแอไม่ได้แปลว่าพ่ายแพ้ แต่เป็นกลไกตามธรรมชาติของร่างกายในการระบายความเครียด</li>
                            <li><strong>พักอย่างมีวินัย:</strong> ตั้งเวลาพักเหนื่อย 15-20 นาทีทุกๆ 2 ชั่วโมง ดื่มน้ำสะอาดและพักผ่อนในร่ม</li>
                            <li><strong>สร้างระบบช่วยเหลือร่วมกัน:</strong> คุยกับเพื่อนบ้านเพื่อแลกเปลี่ยนความช่วยเหลือหรือแบ่งเบาเครื่องมือ อุปกรณ์กันและกัน ความรู้สึกสามัคคีจะลดสภาวะรู้สึกโดดเดี่ยวลงอย่างมาก</li>
                        </ol>
                    '
                ]
            ];
        };

        Route::get('/articles', function () use ($getArticles) {
            $articles = $getArticles();
            return view('mental.articles', compact('articles'));
        })->name('articles');

        Route::get('/articles/{id}', function ($id) use ($getArticles) {
            $article = collect($getArticles())->firstWhere('id', (int)$id);
            if (!$article) {
                abort(404);
            }
            return view('mental.article-show', compact('article'));
        })->name('articles.show');
    });
});

// ============================================================
// VOLUNTEER ROUTES
// ============================================================
Route::middleware(['auth', 'role:volunteer|officer|admin|super_admin'])->prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('/dashboard', [VolunteerController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [VolunteerController::class, 'tasks'])->name('tasks');
    Route::post('/tasks/{sosRequest}/accept', [VolunteerController::class, 'acceptTask'])->name('tasks.accept');
    Route::post('/tasks/{sosRequest}/report', [VolunteerController::class, 'reportTask'])->name('tasks.report');
});

// ============================================================
// OFFICER ROUTES
// ============================================================
Route::middleware(['auth', 'role:officer|admin|super_admin'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [OfficerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sos', [SosRequestController::class, 'officerIndex'])->name('sos.index');
    Route::post('/sos/{sosRequest}/assign', [SosRequestController::class, 'assign'])->name('sos.assign');
    Route::patch('/sos/{sosRequest}/status', [SosRequestController::class, 'updateStatus'])->name('sos.status');
    Route::get('/missing-persons', [MissingPersonController::class, 'officerIndex'])->name('missing.index');
    Route::patch('/missing-persons/{missingPerson}/status', [MissingPersonController::class, 'updateStatus'])->name('missing.status');
    Route::get('/hazard-reports', [HazardReportController::class, 'officerIndex'])->name('hazard.index');
    Route::patch('/hazard-reports/{hazardReport}/verify', [HazardReportController::class, 'verify'])->name('hazard.verify');
});

// ============================================================
// MENTAL OFFICER ROUTES
// ============================================================
Route::middleware(['auth', 'role:mental_officer|admin|super_admin'])->prefix('mental-officer')->name('mental-officer.')->group(function () {
    Route::get('/dashboard', [MentalDashboardController::class, 'index'])->name('dashboard');
    Route::get('/assessments', [MentalAssessmentController::class, 'officerIndex'])->name('assessments');
    Route::get('/assessments/{mentalAssessment}', [MentalAssessmentController::class, 'officerShow'])->name('assessments.show');
    Route::get('/appointments', [AppointmentController::class, 'officerIndex'])->name('appointments');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('relief-points', ReliefPointController::class)->except(['show']);
    Route::resource('resources', ResourceController::class)->except(['show']);
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateRole'])->name('users.role');
    Route::get('/alerts', [AlertController::class, 'adminIndex'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    Route::get('/alerts/{alert}/edit', [AlertController::class, 'edit'])->name('alerts.edit');
    Route::patch('/alerts/{alert}', [AlertController::class, 'update'])->name('alerts.update');
    Route::delete('/alerts/{alert}', [AlertController::class, 'destroy'])->name('alerts.destroy');
    // News admin CRUD
    Route::get('/news', [NewsController::class, 'adminIndex'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
});

// ============================================================
// SUPER ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [SuperAdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/users', [SuperAdminDashboardController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role', [SuperAdminDashboardController::class, 'updateRole'])->name('users.role');
    Route::get('/shelter-monitoring', [SuperAdminDashboardController::class, 'shelterMonitoring'])->name('shelter');
    Route::get('/resource-monitoring', [SuperAdminDashboardController::class, 'resourceMonitoring'])->name('resources');
});

require __DIR__ . '/auth.php';
