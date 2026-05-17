<?php

namespace App\Traits;

trait ParsesSearchQueries
{
    protected $validInstitutions = ['SSC', 'HSC', 'BCS', 'Admission', 'Departmental', 'Primary', 'Bank'];
    protected $validBoards = ['Dhaka', 'Chittagong', 'Comilla', 'Rajshahi', 'Jessore', 'Sylhet', 'Barisal', 'Mymensingh', 'Dinajpur', 'Madrasah', 'Technical'];
    protected $validCategories = ['mcq', 'cq', 'writing', 'image'];
    protected $validSubjects = [
        'Bangla 1st', 'Bangla 2nd', 'English 1st', 'English 2nd', 'ICT', 
        'Physics 1st', 'Physics 2nd', 'Chemistry 1st', 'Chemistry 2nd',
        'Biology 1st', 'Biology 2nd', 'Higher Math 1st', 'Higher Math 2nd',
        'Bangla', 'English', 'General Science', 'General Math', 'Bangladesh Affairs', 
        'International Affairs', 'Geography', 'Computer', 'Mental Ability', 
        'Morality, Values & Good Governance', 'Physics', 'Chemistry', 'Biology', 'Finance', 'Accounting', 'Management', 'Marketing'
    ];

    public function parseSearchQuery(string $q): array
    {
        // --- 1. FAST TRACK ID MATCHING ---
        // Anchored to start (^) and end ($) so "502 physics" doesn't trigger this.
        if (preg_match('/^(?:id#|#)?(\d+)$/i', trim($q), $idMatch)) {
            return ['id' => $idMatch[1]];
        }
        
        $q = $this->translateBnToEn($q);
        $q = preg_replace('/\s+/', ' ', trim($q));
        
        $data = [
            'category'    => null, 
            'year'        => null, 
            'institution' => null, 
            'board'       => null, 
            'chapter'     => null, 
            'subject'     => null,
            'clean_query' => '' 
        ];

        // 1. Extract Category
        if (preg_match('/\b(cq|mcq|writing|image)\b/i', $q, $catMatch)) {
            $data['category'] = strtolower($catMatch[0]);
            $q = preg_replace('/\b'.preg_quote($catMatch[0], '/').'\b/i', '', $q);
        }

        // 2. Extract Year / BCS Ordinal
        if (preg_match('/\b(20[0-9]{2})\b/', $q, $match)) {
            $data['year'] = $match[1];
            $q = str_replace($match[0], '', $q);
        } 
        elseif (preg_match('/\b(\d{2})(?:st|nd|rd|th)?\b/i', $q, $match)) {
            $val = (int)$match[1];
            if ($val >= 10 && $val <= 55) {
                if (!preg_match('/\b' . $val . '(?:st|nd|rd|th)?\s+(chapter|ch|adhay|oddhay|odday)\b/i', $q)) {
                    $data['year'] = $val;
                    $q = str_replace($match[0], '', $q);
                }
            }
        }

        // 3. CHAPTER EXTRACTION
        $chapKeywords = ['chapter', 'ch', 'adhay', 'oddhay', 'odday'];
        $chapPattern = '/(?:(\d{1,2})(?:st|nd|rd|th)?\s+(?:' . implode('|', $chapKeywords) . '))|(?:(?:' . implode('|', $chapKeywords) . ')\s+(\d{1,2}))/i';
        
        if (preg_match($chapPattern, $q, $match)) {
            $data['chapter'] = (int)($match[1] ?: $match[2]);
            $q = str_replace($match[0], '', $q);
        }

        // 4. Identify Institution
        foreach ($this->validInstitutions as $inst) {
            if (preg_match("/\b" . preg_quote($inst, '/') . "\b/i", $q, $match)) {
                $data['institution'] = $inst;
                $q = preg_replace("/\b" . preg_quote($match[0], '/') . "\b/i", '', $q);
                break;
            }
        }

        // 5. Identify Board
        foreach ($this->validBoards as $board) {
            if (preg_match("/\b" . preg_quote($board, '/') . "\b/i", $q, $match)) {
                $data['board'] = $board;
                $q = preg_replace("/\b" . preg_quote($match[0], '/') . "\b/i", '', $q);
                break;
            }
        }

        // 6. SUBJECT MATCHING
        $subjectsByLength = $this->validSubjects;
        usort($subjectsByLength, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($subjectsByLength as $vs) {
            if (preg_match("/\b" . preg_quote($vs, '/') . "\b/i", $q, $match)) {
                $data['subject'] = $vs;
                $q = preg_replace("/\b" . preg_quote($match[0], '/') . "\b/i", '', $q);
                break; 
            }
        }

        // 7. Final Cleanup
        $fillers = ['the', 'of', 'in', 'and', 'question', 'answer', 'solution', 'for', 'from', 'paper', 'papr', 'set', 'solve'];
        $q = preg_replace('/\b('.implode('|', $fillers).')\b/i', '', $q);
        $q = preg_replace('/\b('.implode('|', $chapKeywords).')\b/i', '', $q);
        
        $data['clean_query'] = trim(preg_replace('/\s+/', ' ', $q));

        return array_filter($data);
    }

    private function translateBnToEn(string $q): string
    {
        $bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $enDigits = ['0','1','2','3','4','5','6','7','8','9'];
        $q = str_replace($bnDigits, $enDigits, $q);

        $dict = [
            'পদার্থবিজ্ঞান' => 'Physics', 'পদার্থ বিজ্ঞান' => 'Physics',
            'জীববিজ্ঞান' => 'Biology', 'জীব বিজ্ঞান' => 'Biology',
            'রসায়নবিজ্ঞান' => 'Chemistry', 'রসায়ন বিজ্ঞান' => 'Chemistry', 'রসায়ন' => 'Chemistry',
            'উচ্চতর গণিত' => 'Higher Math', 'উচ্চতরগনিত' => 'Higher Math',
            'সাধারণ গণিত' => 'General Math', 'সাধারন গণিত' => 'General Math',
            'সাধারণ বিজ্ঞান' => 'General Science', 'সাধারন বিজ্ঞান' => 'General Science',
            'হিসাব বিজ্ঞান' => 'Accounting', 'হিসাববিজ্ঞান' => 'Accounting',
            'বাংলাদেশ বিষয়াবলী' => 'Bangladesh Affairs', 'আন্তর্জাতিক বিষয়াবলী' => 'International Affairs',
            'পৌরনীতি' => 'Civics', 'অর্থনীতি' => 'Economics', 'ভূগোল' => 'Geography',
            'ফিন্যান্স' => 'Finance', 'ব্যবস্থাপনা' => 'Management',
            'মানসিক দক্ষতা' => 'Mental Ability', 'নৈতিকতা' => 'Morality',
            'বিভাগীয়' => 'Departmental', 'প্রাথমিক' => 'Primary', 'প্রাইমারি' => 'Primary',
            'বিসিএস' => 'BCS', 'চাকরি' => 'BCS', 'চাকরী' => 'BCS', 'জব' => 'BCS',
            'ভর্তি পরীক্ষা' => 'Admission', 'ভর্তি' => 'Admission', 'এডমিশন' => 'Admission',
            'ব্যাংক' => 'Bank', 'ব্যাঙ্ক' => 'Bank',
            'ঢাকা' => 'Dhaka', 'রাজশাহী' => 'Rajshahi', 'কুমিল্লা' => 'Comilla', 
            'যশোর' => 'Jessore', 'চট্টগ্রাম' => 'Chittagong', 'বরিশাল' => 'Barisal', 
            'সিলেট' => 'Sylhet', 'দিনাজপুর' => 'Dinajpur', 'ময়মনসিংহ' => 'Mymensingh',
            'মাদ্রাসা' => 'Madrasah', 'কারিগরি' => 'Technical',
            'অধ্যায়' => 'chapter', 'অদ্ধায়' => 'chapter', 'পাঠ' => 'chapter',
            'প্রথম' => '1st', '১ম পত্র' => '1st paper', '১ম' => '1st',
            'দ্বিতীয়' => '2nd', '২য় পত্র' => '2nd paper', '২য়' => '2nd',
            'তৃতীয়' => '3rd', '৩য়' => '3rd',
            'চতুর্থ' => '4th', '৪র্থ' => '4th',
            'পত্র' => 'paper', 'পেপ্যার' => 'paper', 'প্যাপার' => 'paper',
            'বিষয়' => 'subject', 'সাবজেক্ট' => 'subject',
            'বাংলা' => 'Bangla', 'ইংরেজি' => 'English', 'ইংরেজী' => 'English', 'ইরেজী' => 'English',
            'ফিজিক্স' => 'Physics', 'কেমিস্ট্রি' => 'Chemistry', 'বায়োলজি' => 'Biology',
            'ম্যাথ' => 'Math', 'অংক' => 'Math', 'বিজ্ঞান' => 'General Science', 'আইসিটি' => 'ICT',
            'তথ্য ও যোগাযোগ প্রযুক্তি' => 'ICT', 'science' => 'General Science'
        ];

        foreach ($dict as $bn => $en) {
            $q = str_replace($bn, $en, $q);
        }

        return $q;
    }
}