# RescueMind
## National Disaster Management & Mental Resilience Platform

Version: 1.0 Final Architecture
Status: Complete System Specification
Scope: Multi-Province Disaster Management + Mental Health Recovery Platform

---

# 1. Executive Summary

RescueMind คือแพลตฟอร์มบริหารจัดการภัยพิบัติและฟื้นฟูสุขภาพจิตแบบครบวงจร ที่ออกแบบมาเพื่อรองรับวงจรการจัดการภัยพิบัติทั้งหมด ตั้งแต่ก่อนเกิดภัย ระหว่างเกิดภัย หลังเกิดภัย และการบริหารจัดการระดับศูนย์บัญชาการ

ระบบถูกพัฒนาภายใต้แนวคิด

> "One Platform for Disaster Preparedness, Emergency Response, Mental Recovery and Command Analytics"

โดยมีเป้าหมายเพื่อลดความสูญเสียทั้งด้านชีวิต ทรัพย์สิน และสุขภาพจิตของประชาชน

---

# 2. ปัญหาที่พบในปัจจุบัน

## 2.1 ก่อนเกิดภัย

ประชาชนไม่ทราบ

- พื้นที่เสี่ยงภัย
- ระดับความรุนแรง
- จุดอพยพ
- ศูนย์พักพิง
- โรงพยาบาลใกล้เคียง

ข้อมูลกระจัดกระจายอยู่หลายช่องทาง

- Facebook
- Line
- ข่าว
- เว็บไซต์หน่วยงานรัฐ

---

## 2.2 ระหว่างเกิดภัย

ปัญหาที่พบ

- ขอความช่วยเหลือได้ยาก
- ไม่ทราบพิกัดผู้ประสบภัย
- หน่วยกู้ภัยได้รับข้อมูลไม่ครบ
- จัดลำดับความเร่งด่วนไม่ได้
- ขาดระบบติดตามสถานะการช่วยเหลือ

---

## 2.3 หลังเกิดภัย

ผู้ประสบภัยจำนวนมากได้รับผลกระทบทางจิตใจ

เช่น

- ความเครียด
- PTSD
- วิตกกังวล
- ภาวะซึมเศร้า

แต่ไม่มีระบบติดตามและฟื้นฟูอย่างต่อเนื่อง

---

## 2.4 ระดับหน่วยงาน

หน่วยงานขาดข้อมูลแบบรวมศูนย์

ไม่สามารถทราบได้ว่า

- พื้นที่ใดวิกฤตที่สุด
- ต้องส่งทรัพยากรไปที่ใด
- ศูนย์พักพิงใกล้เต็มหรือไม่
- โรงพยาบาลรองรับได้อีกหรือไม่

---

# 3. วัตถุประสงค์ของระบบ

1. แจ้งเตือนภัยล่วงหน้าให้ประชาชน
2. ช่วยให้ผู้ประสบภัยสามารถขอความช่วยเหลือได้ทันที
3. เพิ่มประสิทธิภาพการช่วยเหลือของเจ้าหน้าที่
4. ฟื้นฟูสุขภาพกายและจิตใจหลังภัยพิบัติ
5. สนับสนุนการตัดสินใจของศูนย์บัญชาการ
6. เชื่อมโยงข้อมูลทุกหน่วยงานเข้าสู่แพลตฟอร์มเดียว

---

# 4. วิสัยทัศน์ของระบบ

RescueMind เป็น

National Disaster Management & Mental Resilience Platform

ที่ครอบคลุม

- Prevention
- Preparedness
- Response
- Recovery
- Analytics

ในระบบเดียว

---

# 5. สถาปัตยกรรม 4 มิติ

---

# Dimension 1
# Early Warning & Preparedness

## เป้าหมาย

ลดความสูญเสียก่อนเกิดภัย

---

## ฟังก์ชัน

### Flash Alert

ระดับการแจ้งเตือน

- ระดับ 1 เฝ้าระวัง
- ระดับ 2 เตรียมอพยพ
- ระดับ 3 อพยพทันที

---

### Risk Zone Map

แสดงพื้นที่เสี่ยง

- น้ำท่วม
- ดินถล่ม
- พายุ
- ไฟป่า
- PM2.5

---

### Relief Point Locator

แสดง

- ศูนย์พักพิง
- โรงพยาบาล
- ศูนย์อาหาร
- จุดจอดรถหนีน้ำ

---

### Evacuation Route

แนะนำเส้นทางอพยพ

---

### Preparedness Checklist

รายการเตรียมพร้อม

เช่น

- ยา
- อาหาร
- เอกสารสำคัญ
- ชุดปฐมพยาบาล

---

### AI Calm Mode

AI ช่วยลดความตื่นตระหนก

---

# Dimension 2
# Emergency Response

## เป้าหมาย

ช่วยเหลือผู้ประสบภัยให้รวดเร็วที่สุด

---

## SOS System

ผู้ใช้กดปุ่ม SOS

ระบบจะ

1. ขอ GPS
2. ดึงพิกัด
3. บันทึกเคส
4. แจ้งเจ้าหน้าที่
5. เปิดกระบวนการช่วยเหลือ

---

## ข้อมูลที่ส่ง

- พิกัด
- จำนวนผู้ประสบภัย
- ระดับน้ำ
- ผู้สูงอายุ
- เด็ก
- ผู้ป่วยติดเตียง
- หญิงตั้งครรภ์

---

## Workflow

Pending

↓

Assigned

↓

In Progress

↓

Resolved

↓

Safe

---

## AI Triage

วิเคราะห์ความเร่งด่วน

ระดับ

- Critical
- High
- Medium
- Low

---

## Officer Dispatch

เจ้าหน้าที่รับเคส

- ดูพิกัด
- รับงาน
- นำทาง
- เปลี่ยนสถานะ

---

## Volunteer Dispatch

ระบบจัดการอาสาสมัคร

สามารถ

- รับเคส
- รายงานสถานการณ์
- ช่วยเหลือเบื้องต้น

---

# Dimension 3
# Recovery & Mental Health

## เป้าหมาย

ฟื้นฟูสุขภาพจิตและคุณภาพชีวิต

---

## Detect

แบบประเมิน

### PHQ-9

ประเมินภาวะซึมเศร้า

### GAD-7

ประเมินความวิตกกังวล

### PTSD Assessment

ประเมินความเครียดหลังเหตุการณ์รุนแรง

---

## Support

### AI Companion

สนทนาและให้กำลังใจ

---

### Mood Tracker

ติดตามอารมณ์

---

### Journal

บันทึกความรู้สึก

---

### Sentiment Analysis

วิเคราะห์แนวโน้มทางอารมณ์

---

## Connect

เชื่อมต่อผู้เชี่ยวชาญ

- นักจิตวิทยา
- จิตแพทย์
- นักสังคมสงเคราะห์
- โรงพยาบาล

---

## Video Consultation

ปรึกษาผู้เชี่ยวชาญออนไลน์

---

## Follow-Up

ติดตามผล

- 7 วัน
- 14 วัน
- 30 วัน
- 90 วัน

---

# Dimension 4
# Command & Analytics

## เป้าหมาย

สนับสนุนการตัดสินใจของศูนย์บัญชาการ

---

## Command Center Dashboard

แสดง

- SOS ทั้งหมด
- เคสที่กำลังดำเนินการ
- เคสที่เสร็จสิ้น
- สถิติรายวัน

---

## Heatmap

แสดงพื้นที่วิกฤติ

---

## Shelter Monitoring

ติดตามศูนย์พักพิง

- ความจุ
- จำนวนผู้พัก
- จำนวนที่เหลือ

---

## Hospital Monitoring

ติดตามโรงพยาบาล

- เตียงว่าง
- ICU
- รถพยาบาล

---

## Resource Monitoring

ติดตาม

- เรือ
- รถ
- ยา
- อาหาร
- น้ำดื่ม

---

## KPI Dashboard

- Response Time
- Rescue Time
- Recovery Rate
- Resource Efficiency

---

## Predictive Analytics

คาดการณ์

- พื้นที่เสี่ยง
- ความต้องการทรัพยากร
- ปริมาณ SOS

---

# 6. Additional Core Modules

---

## Missing Person System

แจ้งคนหาย

สถานะ

- Missing
- Searching
- Found
- Safe

ข้อมูล

- รูปภาพ
- ชื่อ
- อายุ
- พิกัดล่าสุด

---

## Family Safety Check

ผู้ใช้กด

"ฉันปลอดภัยแล้ว"

ครอบครัวตรวจสอบได้

---

## Crowdsource Hazard Report

ประชาชนแจ้ง

- น้ำท่วม
- ดินถล่ม
- ถนนขาด
- สะพานพัง
- ไฟฟ้าดับ

พร้อมรูปภาพ

---

## Resource Logistics

บริหารทรัพยากร

- เรือ
- รถ
- อาหาร
- ยา
- น้ำดื่ม

---

## Donation Management

จับคู่

ผู้บริจาค ↔ ผู้ต้องการ

---

## Emergency Broadcast

ส่ง

- Push Notification
- Email
- SMS
- LINE

---

## Drone & Satellite Integration

เชื่อมข้อมูล

- โดรน
- ภาพถ่ายดาวเทียม

---

## PWA Offline Mode

รองรับ

- สัญญาณอ่อน
- อินเทอร์เน็ตล่ม

---

# 7. Roles & Permissions

---

## Role 1
Guest

สิทธิ์

- ดูข้อมูล
- ดูแผนที่
- ดูศูนย์พักพิง

---

## Role 2
User

สิทธิ์

- กด SOS
- แจ้งคนหาย
- ประเมินสุขภาพจิต
- ใช้งาน AI Companion

---

## Role 3
Volunteer

สิทธิ์

- รับงานอาสา
- รายงานสถานการณ์
- ช่วยค้นหาคนหาย

---

## Role 4
Officer

สิทธิ์

- รับเคส SOS
- เปลี่ยนสถานะ
- จัดการภารกิจช่วยเหลือ

---

## Role 5
Mental Officer

สิทธิ์

- ดูผลประเมิน
- นัดหมาย
- ติดตามผู้ป่วย

---

## Role 6
Admin

สิทธิ์

- จัดการจังหวัด
- จัดการเจ้าหน้าที่
- จัดการศูนย์พักพิง
- ดู Analytics จังหวัด

---

## Role 7
Super Admin

สิทธิ์สูงสุด

- จัดการทุกจังหวัด
- จัดการทุกผู้ใช้
- ตั้งค่าระบบ
- Override ทุกสิทธิ์
- ดูข้อมูลทั้งประเทศ

---

# 8. Multi Province Architecture

รองรับ

ประเทศ
↓
จังหวัด
↓
อำเภอ
↓
ตำบล

Admin เห็นเฉพาะพื้นที่ตัวเอง

Super Admin เห็นทุกพื้นที่

---

# 9. AI Architecture

ใช้ Hybrid AI

---

## Rule-Based Engine

ใช้สำหรับ

- SOS Priority
- Dispatch Logic
- Risk Scoring

---

## LLM AI

ใช้สำหรับ

- AI Companion
- Mental Support
- Sentiment Analysis
- Summarization
- Predictive Analytics

รองรับ

- OpenAI
- Gemini

---

# 10. Technology Stack

## Backend

- Laravel 12
- PHP 8.4
- MySQL 8
- Redis
- Laravel Reverb
- Queue Worker
- Sanctum
- Spatie Permission

---

## Frontend

- Blade
- Tailwind CSS
- Alpine.js
- Livewire 3
- Leaflet.js

---

## Authentication

- Laravel Breeze
- MFA
- OTP

---

## Mapping

- Leaflet.js
- Google Maps Deep Link

---

## Mobile

- PWA
- Service Worker
- IndexedDB

---

# 11. Key Innovation

RescueMind ไม่ได้เป็นเพียงระบบแจ้งเหตุฉุกเฉิน

แต่เป็นแพลตฟอร์มที่รวม

1. Disaster Preparedness
2. Emergency Response
3. Mental Health Recovery
4. Command & Analytics

ไว้ในระบบเดียว

โดยเชื่อมโยงการบริหารจัดการภัยพิบัติกับการดูแลสุขภาพจิตของประชาชนอย่างครบวงจร

ซึ่งเป็นแนวทางที่แตกต่างจากระบบเตือนภัยหรือระบบ SOS ทั่วไป และสามารถขยายผลสู่ระดับจังหวัดและระดับประเทศได้ในอนาคต

---

# 12. Database Architecture & ERD

## Core Tables

### users
```
id (PK)
name
email
phone
password
avatar
role
province_id (FK)
status (active/inactive)
created_at
updated_at
```

### models_hierarchy
```
User (base user)
├── Volunteer (has volunteer_id)
├── Officer (has officer_id, badge_number)
├── Mental Officer (has mental_officer_id, license)
└── Admin (has admin_id, province_id)
```

---

## Emergency & Response Tables

### sos_requests
```
id (PK)
user_id (FK)
latitude
longitude
address
status (pending/assigned/in_progress/resolved/safe)
severity (critical/high/medium/low)
description
people_count
elderly_count
children_count
bedridden_count
pregnant_count
created_at
updated_at
```

### assignments
```
id (PK)
sos_request_id (FK)
officer_id (FK)
volunteer_id (FK - nullable)
status (assigned/accepted/in_progress/completed)
assigned_at
started_at
completed_at
```

### hazard_reports
```
id (PK)
user_id (FK)
report_type (flood/landslide/storm/fire/pm25)
latitude
longitude
description
image_path
severity (critical/high/medium/low)
verified_count
status (pending/verified/dismissed)
created_at
updated_at
```

---

## Missing Person Tables

### missing_persons
```
id (PK)
name
age
gender
image_path
description
last_seen_location
latitude
longitude
contact_person
contact_phone
status (missing/searching/found/safe)
created_at
updated_at
```

### missing_person_searches
```
id (PK)
missing_person_id (FK)
volunteer_id (FK)
latitude
longitude
found_status (searching/found)
created_at
```

---

## Mental Health Tables

### mental_assessments
```
id (PK)
user_id (FK)
assessment_type (phq9/gad7/ptsd)
score
interpretation (normal/mild/moderate/severe)
created_at
updated_at
```

### mood_logs
```
id (PK)
user_id (FK)
mood_score (1-10)
emotion (happy/sad/angry/scared/calm)
note
created_at
```

### journal_entries
```
id (PK)
user_id (FK)
title
content
sentiment_score (-1 to 1)
tags
created_at
updated_at
```

---

## Infrastructure Tables

### relief_points
```
id (PK)
name
location_type (shelter/food_center/water_point)
latitude
longitude
province_id (FK)
capacity
current_occupancy
contact_person
contact_phone
status (active/full/closed)
created_at
updated_at
```

### resources
```
id (PK)
resource_type (boat/car/ambulance/medicine/food/water)
status (available/in_use/maintenance)
province_id (FK)
last_location
operator_id (FK - nullable)
created_at
updated_at
```

### appointments
```
id (PK)
user_id (FK)
mental_officer_id (FK)
appointment_type (consultation/followup)
scheduled_at
status (scheduled/completed/cancelled)
notes
created_at
updated_at
```

---

## Hierarchical Tables

### provinces
```
id (PK)
name
region
code
created_at
```

### districts
```
id (PK)
province_id (FK)
name
code
created_at
```

### subdistricts
```
id (PK)
district_id (FK)
name
code
created_at
```

---

# 13. API Endpoints Documentation

## Authentication Endpoints

```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
POST   /api/auth/refresh
POST   /api/auth/mfa/request
POST   /api/auth/mfa/verify
GET    /api/auth/me
```

---

## SOS & Emergency Endpoints

```
POST   /api/sos/request              Create SOS request
GET    /api/sos/requests             List SOS requests (officer)
GET    /api/sos/requests/{id}        Get SOS request detail
PATCH  /api/sos/requests/{id}/status Update SOS status
POST   /api/sos/requests/{id}/assign Assign SOS to officer
GET    /api/sos/dashboard            Command center dashboard
GET    /api/sos/analytics/response   Response time analytics
GET    /api/sos/heatmap              Heatmap data
```

---

## Hazard Report Endpoints

```
POST   /api/hazard/report             Create hazard report
GET    /api/hazard/reports            List reports
GET    /api/hazard/reports/{id}       Get report detail
PATCH  /api/hazard/reports/{id}       Update report
POST   /api/hazard/reports/{id}/verify Verify report
GET    /api/hazard/map                Hazard map data
```

---

## Missing Person Endpoints

```
POST   /api/missing-persons           Create missing person report
GET    /api/missing-persons           List missing persons
GET    /api/missing-persons/{id}      Get detail
PATCH  /api/missing-persons/{id}      Update status
POST   /api/missing-persons/{id}/search Start search
GET    /api/missing-persons/search/active Active searches
POST   /api/missing-persons/{id}/found   Mark as found
```

---

## Mental Health Endpoints

```
POST   /api/mental/assessments        Create assessment (PHQ-9/GAD-7/PTSD)
GET    /api/mental/assessments        Get user assessments
GET    /api/mental/assessments/{id}   Get assessment detail
POST   /api/mental/mood-logs          Log mood
GET    /api/mental/mood-logs          Get mood history
POST   /api/mental/journals           Create journal entry
GET    /api/mental/journals           Get journal entries
GET    /api/mental/ai-companion       Chat with AI
POST   /api/mental/ai-companion       Send message to AI
GET    /api/mental/appointments       Get appointments
POST   /api/mental/appointments       Book appointment
```

---

## Relief Point Endpoints

```
GET    /api/relief-points             List relief points
GET    /api/relief-points/{id}        Get detail
GET    /api/relief-points/nearby      Find nearby (lat/lng)
PATCH  /api/relief-points/{id}        Update occupancy
POST   /api/relief-points             Create relief point (admin)
```

---

## Resource Management Endpoints

```
GET    /api/resources                 List resources
GET    /api/resources/{id}            Get detail
PATCH  /api/resources/{id}            Update resource status
POST   /api/resources                 Create resource (admin)
GET    /api/resources/dispatch        Get available for dispatch
```

---

## Officer & Dispatch Endpoints

```
GET    /api/officers                  List officers (admin)
GET    /api/officers/{id}             Get officer detail
PATCH  /api/officers/{id}             Update officer
GET    /api/officers/workload         Get officer workload
GET    /api/officers/assignments      Get my assignments
PATCH  /api/officers/assignments/{id} Update assignment status
```

---

## Volunteer Endpoints

```
GET    /api/volunteers                List volunteers (admin)
POST   /api/volunteers/register       Register as volunteer
GET    /api/volunteers/{id}           Get profile
PATCH  /api/volunteers/{id}           Update profile
GET    /api/volunteers/tasks          Get available tasks
POST   /api/volunteers/tasks/{id}     Accept task
PATCH  /api/volunteers/tasks/{id}     Report task status
```

---

## Analytics & Reporting Endpoints

```
GET    /api/analytics/dashboard       Command center dashboard
GET    /api/analytics/kpi             KPI metrics
GET    /api/analytics/response-time   Response time stats
GET    /api/analytics/resource-usage  Resource usage stats
GET    /api/analytics/mental-health   Mental health statistics
GET    /api/analytics/predictions     Predictive analytics
GET    /api/reports/export            Export reports
```

---

## Admin & System Endpoints

```
GET    /api/admin/provinces           List provinces
GET    /api/admin/districts           List districts
GET    /api/admin/subdistricts        List subdistricts
POST   /api/admin/users               Create user (admin)
PATCH  /api/admin/users/{id}          Update user (admin)
DELETE /api/admin/users/{id}          Delete user (admin)
POST   /api/admin/relief-points       Create relief point
PATCH  /api/admin/relief-points/{id}  Update relief point
```

---

# 14. Implementation Roadmap

## Phase 1: Foundation (Week 1-2)
- [ ] Complete database migrations
- [ ] Setup authentication (Sanctum)
- [ ] Implement base Models
- [ ] Create SOS Request model & controller
- [ ] Setup role-based permissions

## Phase 2: Emergency Response (Week 3-4)
- [ ] Build SOS handling system
- [ ] Implement officer dispatch logic
- [ ] Create assignment workflow
- [ ] Add real-time notifications
- [ ] Build command center dashboard

## Phase 3: Mental Health (Week 5-6)
- [ ] Build assessment system (PHQ-9, GAD-7, PTSD)
- [ ] Create mood tracker
- [ ] Build journal feature
- [ ] Integrate AI companion (basic)
- [ ] Create mental health dashboard

## Phase 4: Advanced Features (Week 7-8)
- [ ] Missing person system
- [ ] Hazard reporting
- [ ] Resource management
- [ ] Volunteer management
- [ ] Relief point monitoring

## Phase 5: Analytics & Optimization (Week 9-10)
- [ ] Implement analytics engine
- [ ] Build predictive models
- [ ] Create heatmap visualization
- [ ] Performance optimization
- [ ] Load testing

## Phase 6: Deployment & Testing (Week 11-12)
- [ ] Full system integration testing
- [ ] Security audit
- [ ] PWA offline testing
- [ ] Production deployment
- [ ] User training

---

# 15. File Structure Convention

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php
│   │   │   ├── SosController.php
│   │   │   ├── MentalHealthController.php
│   │   │   ├── MissingPersonController.php
│   │   │   ├── HazardReportController.php
│   │   │   ├── OfficerController.php
│   │   │   ├── AnalyticsController.php
│   │   │   └── AdminController.php
│   │   └── Web/
│   ├── Requests/
│   │   ├── StoreSosRequest.php
│   │   ├── MentalAssessmentRequest.php
│   │   └── ...
│   └── Resources/
│       ├── SosResource.php
│       ├── MentalAssessmentResource.php
│       └── ...
├── Models/
│   ├── User.php
│   ├── SosRequest.php
│   ├── MentalAssessment.php
│   ├── MissingPerson.php
│   ├── HazardReport.php
│   ├── Officer.php
│   ├── Volunteer.php
│   ├── ReliefPoint.php
│   └── ...
├── Services/
│   ├── SosService.php
│   ├── DispatchService.php
│   ├── MentalHealthService.php
│   ├── AnalyticsService.php
│   └── ...
└── Events/
    ├── SosCreated.php
    ├── SosAssigned.php
    ├── SosResolved.php
    └── ...
```

---

END OF DOCUMENT
RescueMind Final Specification v1.0 - Extended with API, Database & Roadmap