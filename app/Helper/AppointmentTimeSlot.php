<?php
namespace App\Helper;

class AppointmentTimeSlot{
    public static function available($removed_times, $interval = 100) {
        $time_array = array();
    
        for ($i = 900; $i <= 2400; $i += $interval) {
            
            $formatted_time = sprintf('%02d.%02d', floor($i / 100), $i % 100);
            if ($i < 1200) {
                $formatted_time .= " AM";
            } else {
                $formatted_time .= " PM";
            }
    
            $id = sprintf('%04d', $i);
            if (!in_array($id, $removed_times)) {
                $time_array[$id] = array('id' => $id, 'label' => $formatted_time);
            }
        }
    
        return $time_array;
    }

    public static function slots($interval = 10, $maximum = 10) {
        
        $intervals = [];
        for ($i = 1; $i <= $maximum; $i++) {$minutes = $i * $interval;
            if ($minutes < 60) {
                $label = "{$minutes} min";
            } else {
                $hours = floor($minutes / 60);
                $remainingMinutes = $minutes % 60;
                $label = "{$hours} hour" . ($hours > 1 ? "s" : "") . ($remainingMinutes > 0 ? " {$remainingMinutes} min" : "");
            }
            $intervals[] = [
                'id' => $minutes,
                'label' => $label
            ];
        }
        
        return $intervals;
    }


}