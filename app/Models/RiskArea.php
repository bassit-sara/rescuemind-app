<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskArea extends Model
{
    protected $fillable = [
        'province', 'district', 'subdistrict', 'area_name',
        'latitude', 'longitude',
        'rain_score', 'water_score', 'sos_count', 'missing_count', 'hazard_count',
        'risk_score', 'risk_level', 'prediction_text'
    ];

    /**
     * Calculate Risk Score based on the formula:
     * (Rain Score * 0.3) + (Water Score * 0.3) + (SOS Score * 0.2) + (Missing Score * 0.1) + (Hazard Score * 0.1)
     */
    public function calculateRiskScore(): void
    {
        // Simple mapping: SOS count 1 = 10 score (max 100), Missing count 1 = 20 score (max 100), Hazard count 1 = 20 score (max 100)
        $sosScore = min($this->sos_count * 10, 100);
        $missingScore = min($this->missing_count * 20, 100);
        $hazardScore = min($this->hazard_count * 20, 100);

        $this->risk_score = round(
            ($this->rain_score * 0.3) +
            ($this->water_score * 0.3) +
            ($sosScore * 0.2) +
            ($missingScore * 0.1) +
            ($hazardScore * 0.1)
        );

        if ($this->risk_score >= 80) {
            $this->risk_level = 'critical';
        } elseif ($this->risk_score >= 60) {
            $this->risk_level = 'warning';
        } elseif ($this->risk_score >= 40) {
            $this->risk_level = 'watch';
        } else {
            $this->risk_level = 'safe';
        }

        // Generate prediction text if score is high
        if ($this->risk_level == 'warning') {
            $this->prediction_text = 'คาดว่าจะเปลี่ยนเป็น Critical Zone ภายใน 3 ชั่วโมง';
        } elseif ($this->risk_level == 'critical') {
            $this->prediction_text = 'อพยพทันที คาดการณ์น้ำท่วมหนักภายใน 2 ชั่วโมง';
        } else {
            $this->prediction_text = 'สถานการณ์ยังสามารถรับมือได้';
        }
    }
}
