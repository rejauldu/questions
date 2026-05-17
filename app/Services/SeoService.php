<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Subject;
use App\Models\Institution;

class SeoService
{
    public function generate(Post $post)
    {
        $subject = Subject::find($post->subject_id);
        $institution = Institution::find($post->institution_id);

        $exam = $this->getExamName($institution?->slug);
        $subjectName = ucfirst($subject?->slug ?? 'General');
        $category = strtoupper($post->category);
        $year = $post->year ?? null;

        // 🔥 Format year properly
        $formattedYear = $this->formatYear($exam, $year);

        /*
        |--------------------------------------------------------------------------
        | ✅ TITLE (SMART LOGIC)
        |--------------------------------------------------------------------------
        */

        if ($exam === 'BCS' && $formattedYear) {
            // 🔥 Short + powerful (BEST for SEO)
            $title = "{$formattedYear} {$subjectName} {$category} Question with Answer | ExamDao";
        } else {
            // ✅ Standard format
            $title = "{$exam} {$subjectName} {$category} Question";

            if ($formattedYear) {
                $title .= " ({$formattedYear})";
            }

            $title .= " with Answer | ExamDao";
        }

        /*
        |--------------------------------------------------------------------------
        | ✅ DESCRIPTION
        |--------------------------------------------------------------------------
        */

        if ($exam === 'BCS' && $formattedYear) {
            $description = "Solve {$formattedYear} {$subjectName} {$category} question with full answer and explanation. Practice more on ExamDao.";
        } else {
            $description = "Solve this {$exam} {$subjectName} {$category} question";

            if ($formattedYear) {
                $description .= " from {$formattedYear}";
            }

            $description .= ". Full answer and explanation provided. Practice more on ExamDao.";
        }

        return [
            'title' => $title,
            'description' => $description,
        ];
    }

    /**
     * 🔥 Handle BCS vs HSC/SSC year difference
     */
    private function formatYear($exam, $year)
    {
        if (!$year) return null;

        // 👉 BCS = exam number (10th, 45th)
        if ($exam === 'BCS') {
            return $this->ordinal($year) . " BCS";
        }

        // 👉 HSC / SSC = real year
        if (in_array($exam, ['HSC', 'SSC'])) {
            return $year;
        }

        return $year;
    }

    /**
     * 🔥 Convert number to ordinal (1st, 2nd, 3rd...)
     */
    private function ordinal($number)
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1: return $number . 'st';
                case 2: return $number . 'nd';
                case 3: return $number . 'rd';
            }
        }
        return $number . 'th';
    }

    private function getExamName($slug)
    {
        return match($slug) {
            'hsc' => 'HSC',
            'ssc' => 'SSC',
            'bcs' => 'BCS',
            default => strtoupper($slug ?? 'Exam')
        };
    }
}